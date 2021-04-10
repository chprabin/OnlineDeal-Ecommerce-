define(['request','ajaxform','list'],function(Request, AjaxForm, List){
  class Item{
     constructor(elem){
      this.elem=elem;
      this.selected=false;
      this.isMaster=this.elem.classList.
      contains('master')?true:false;
      this.deleteForm=null;
      this.deleteFormSubmitCallback=null;
      this.initDeleteForm();
     }    
     serverSelected(){
         var box=this.getSelectBox();
         if(!box)
          return false;
         return box.classList.contains('selected'); 
     }
     initDeleteForm(){
         var form=this.elem.querySelector('.delete-form');
         if(form){
           this.deleteForm=new AjaxForm();
           this.deleteForm.setForm(form);
           var this2=this;
           this.deleteForm.onSend(function(f, result){
             if(this2.deleteFormSubmitCallback){
                 this2.deleteFormSubmitCallback(result);
             }
           });
           this.deleteForm.init();
         }
     }
     disableDeleteForm(){
       if(this.deleteForm){
         this.deleteForm.disable();
       }  
     }
     enableDeleteForm(){
        if(this.deleteForm){
         this.deleteForm.enable();
        } 
     }
     select(){
       var box=this.getSelectBox();
       if(!box){
         return;
       }
       box.classList.add('selected');
       this.selected=true;
       this.enableDeleteForm();  
     }
     unselect(){
         var box=this.getSelectBox();
         if(!box){
             return;
         }
         box.classList.remove('selected');
         this.selected=false;
         this.disableDeleteForm();      
     }
     onSelect(callback){
         var box=this.getSelectBox();
         if(box){
           var this2=this;
           box.addEventListener('click',function(e){
             e.preventDefault();
             callback(this2);
           });  
         }
     }
     onDeleteFormSubmit(callback){
         this.deleteFormSubmitCallback=callback;
         return this;
     }
     getId(){
       return this.elem.dataset.id;  
     }
     isClientItem(){
       return this.elem.classList.contains('client-item');  
     }
     clientDelete(){
       if(!this.deleteForm)
        return true;
       return this.deleteForm.form.classList.contains('client-delete');   
     }
     remove(){
         this.elem.remove();
         return this;
     }
     getSelectBox(){
         if(this.elem)
          return  this.elem.querySelector('.select-box');
         return null; 
     }
  }
  return class DataList{
      constructor(config={}){
          this.config=config;
          this.elem=null;
          this.items=[];
          this.selectedIds=[];
          this.req=new Request();
          this.clearAllCallback=null;
          this.insertedData=new List();
          this.insertForm=null;
      }
      initElem(){
       var elem=document.querySelector(this.config.selector);
       if(!elem){
         throw new Error('no data list found with '+this.config.selector);
       }
       this.elem=elem;
       return this;
      }  
 
      initInsertForm(){
       var form=this.elem.querySelector('form.insert-form');
       if(form){
         this.insertForm=new AjaxForm();
         this.insertForm.setForm(form);
         var this2=this;
         this.insertForm.onSend(function(f, result){
           if(result.result){
             if(result.msg){
               f.addMsg(result.result, result.msg);
             }
             this2.addToInsertedData(result.data);
             this2.refresh();
           }else if(result.errors){
             f.addErrors(result.errors);
           }
         }); 
         this.insertForm.init();
       }
      }
      addToInsertedData(obj){
       if(!this.insertedData.find(obj,['id'])){
         this.insertedData.add(obj);
         return true;
       }
       return false;
      }
      refresh(url=null){
       var data={}; 
       if(!url){
         url=this.config.updateUrl;
         data={inserted_data:this.insertedData.toJson(),};
       }
       var res=this.req.get(url, data);
       if(res.view){
         this.update(res.view);
       }
      }
      update(view){
       var updatableView=this.elem.querySelector('.updatable-view');
       $(updatableView).html(view);
       this.initUpdatableComponents();
      }
      initUpdatableComponents(){
       this.initItems();
       this.initSearchForms();
       this.initPagination();
       this.initClearAll();
       this.initRefresherLinks();
      }
      initRefresherLinks(){  
       var links=this.elem.querySelectorAll('a.refresher');
       var this2=this;
       links.forEach(function(link){
         link.addEventListener('click',function(e){
           e.preventDefault();
           this2.refresh(e.target.getAttribute('href'));
         });
       }); 
      }
      initClearAll(){
       var clearAllElem=this.elem.querySelector('.clear-all');
       var this2=this;
       if(clearAllElem){
         clearAllElem.addEventListener('click',function(e){
           e.preventDefault();
           if(this2.selectedIds.length > 0){
             this2.clearAll();
           }
         });
       }
      }
      clearAll(){
        this.unselectAll();
        if(this.clearAllCallback){
         this.clearAllCallback(this);
        }
      }
      initPagination(){
       var links=this.elem.querySelectorAll('.pagination a.page-link');
       var this2=this;
       links.forEach(function(link){
         link.addEventListener('click',function(e){
           e.preventDefault();
           e.stopPropagation();
           addAjaxLoader();
           var res=this2.req.get(link.getAttribute('href'), 
           {inserted_data:this2.insertedData.toJson()});
           if(res.view){
             this2.update(res.view);
             removeAjaxLoader();
           }
         });
       });
      }
      initSearchForms(){
       var forms=this.elem.querySelectorAll('form.search-form');
       var this2=this;
       forms.forEach(function(form){
         var ajaxform=new AjaxForm();
         ajaxform.setForm(form);
         ajaxform.onBeforeSend(function(){
           addAjaxLoader();
           return true;
         });
         ajaxform.onSend(function(f,r){
           if(r && r.view){
             this2.update(r.view);
             removeAjaxLoader();
           }
         });
         ajaxform.init();
       });
      }
      initItems(){
       var itemElems=this.getItemElements();
       var items=[];
       var this2=this;
       itemElems.forEach(function(itemElem){
         var item=new Item(itemElem);
         this2.determineInitialSelection(item);
         this2.initItemSelection(item);
         this2.initItemDeleting(item);
         this2.items.push(item);
       });
      }
      deleteClientItem(item){
       var obj=this.insertedData.findByAttributes({id:item.getId()});
       if(obj){
         this.insertedData.remove(obj);
       }
       this.removeItem(item);
      }    
      initItemDeleting(item){
       if(!item.deleteForm){
        return;
       } 
       var this2=this;
       item.deleteForm.onBeforeSend(function(){
         if(item.clientDelete()){
           this2.deleteClientItem(item);
           return false;
         }
         if(confirm('Are you sure you want to delete?'))
          return true;
         return false; 
       });
       item.onDeleteFormSubmit(function(result){
         if(result.result){
           if(item.isMaster){
             this2.removeAll();
             this2.clearInsertedData();
           }else{
             this2.removeInsertedObject(item.getId());
             this2.removeItem(item);
           }
         }
       });
      }
      clearInsertedData(){
       this.insertedData.clear();       
      }
      removeInsertedObject(id){
       var object=this.insertedData.findByAttributes({id:id});
       if(object){
         this.insertedData.remove(object);
       }
      }
      removeAll(){
        var nonMasterItems=this.getNonMasterItems();
        var this2=this;
        nonMasterItems.forEach(function(it){
         this2.removeItem(it);
        });
      }
      removeItem(item)
      {
       var index=this.items.indexOf(item);
       if(index!=-1){
         this.unselectItem(item);
         item.remove();
       }
      }
      determineInitialSelection(item){
       if(item.serverSelected() ||
        this.selectedIds.includes(item.getId())){
         this.selectItem(item);
       }else{
        if(!item.isMaster){
          this.unselectItem(item);
        }
       }
      }
      initItemSelection(item){
       var this2=this;
       item.onSelect(function(item){
         if(item.selected){
           this2.unselectItem(item);
         }else{
           this2.selectItem(item);
         }
       });
      }
      unselectItem(item){
       if(item.isMaster){
         item.unselect();
         this.unselectAll();
       }else{
         item.unselect();
         this.removeFromSelectedIds(item.getId());
       } 
      }
      unselectAll(){
       var nonMasterItems=this.getNonMasterItems();
       var this2=this;
       nonMasterItems.forEach(function(item){
         this2.unselectItem(item);
       });
      }
      removeFromSelectedIds(id){
       var index=this.selectedIds.indexOf(id);
       if(index!=-1){
         this.selectedIds.splice(index,1);
       }
      }
      selectItem(item){
       if(item.isMaster){
         item.select();
         this.selectAll();
       }else{
         item.select();
         this.addToSelectedIds(item.getId());
       }
      }
      selectAll(){
       var nonMasterItems=this.getNonMasterItems();
       var this2=this;
       nonMasterItems.forEach(function(item){
         this2.unselectItem(item);
         this2.selectItem(item);
       });
      }
      getNonMasterItems(){
       return this.items.filter(function(it){
         return !it.isMaster;
       });
      }
      addToSelectedIds(id){
       if(!this.selectedIds.includes(id)){
         this.selectedIds.push(id);
       }
      }
      getItemElements(){
        return this.elem.querySelectorAll('.data-item');
      }
      init(){
       this.initElem();
       this.initInsertForm();
       this.initUpdatableComponents();
      }
  }
 });