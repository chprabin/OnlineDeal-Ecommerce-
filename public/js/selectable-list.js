define(['request'],function(Request){
  return class SelectableList{
    constructor(config={}){
      /* config={selector:'', remote_loading:false, remote_url:null} */  
      this.elem=null;
      this.config=config;
      this.data_load_timeout=null;
      this.item_selected=false;
      this.req=new Request();
      this.data_load_callback=null;
      this.item_select_callback=null;
      this.init_callback=null;
    }     
    on_data_load(callback){
      this.data_load_callback=callback;
      return this;
    }
    initElem(){   
      var elem=document.querySelector(this.config.selector);
      if(!elem){
          throw new Error('no selectable list is found with '+this.config.selector);
      }
      this.elem=elem;
      return this;
    }
    on_init(callback){
      this.init_callback=callback;
      return this;
    }
    initComponents(){
      this.initControl();
      this.initMenu();
      this.initItems();
      this.initEntry();
      this.initSelectedItem();
      this.initPagination();
    }
    initPagination(){
      var links=this.elem.querySelectorAll('.pagination .page-link');
      var this2=this;
      links.forEach(function(link){
        link.addEventListener('click',function(e){
          e.preventDefault();
          e.stopPropagation();
          this2.load_remote_data(e.target.getAttribute('href'));
          this2.openMenu();
          this2.initPagination();
        });
      });
    }
    initSelectedItem(){
      var selected_item=this.getSelectedItem();
      if(selected_item.textContent.length > 0){
        this.item_selected=true;
      }
    }
    initEntry(){
      var entry=this.getEntry();
      if(entry){
        var this2=this;
        entry.onkeyup=function(e){
          if(this2.config.remote_loading==true){
           if(this2.data_load_timeout){
            clearTimeout(this2.data_load_timeout);
            this2.data_load_timeout=null;
           }  
           this2.data_load_timeout=setTimeout(function(){
            this2.load_remote_data();
            if(this2.getItemsLength()){
             this2.openMenu();
            }
           },300);
          }else{
            this2.filterItems(entry.value);
          }
        }
      }  
    }
    update(view){
      $(this.getMenu()).html(view);
      this.initComponents();
    }
    filterItems(entryValue){
      var items=this.getItems();
      var this2=this;
      items.forEach(function(item){
        if(item.dataset.text.includes(entryValue)){
          this2.displayItem(item);
        }else{
          this2.hideItem(item);
        }
      });
    }
    hideItem(item){
      item.classList.add('hide');
    }
    load_remote_data(url=null){
      if(this.data_load_callback){
        this.data_load_callback(this, url);
      }
    }
    selectItem(item){
      var mainInput=this.getMainInput();
      if(mainInput){
        mainInput.value=item.dataset.value;
      }
      var entry=this.getEntry();
      if(entry){
        entry.value=item.dataset.text;
      }
      this.fillSelectedItem(item);
    }
    fillSelectedItem(item){
      var selectedItem=this.getSelectedItem();
      if(selectedItem){
        selectedItem.textContent=item.dataset.text;
      }
    } 
    initItems(){
      var items=this.getItems();
      var this2=this;
      items.forEach(function(item){
        item.addEventListener('click',function(e){
          e.preventDefault();
          this2.selectItem(item);
          this2.hideEntry();
          this2.deactivateControl();
          this2.closeMenu();
          this2.displayItems();
          this2.item_selected=true;
          if(this2.item_select_callback){
            this2.item_select_callback(this2, item.dataset.value);
          }
        });
      });
    }
    on_select(callback){
      this.item_select_callback=callback;
      return this;
    }
    hideEntry(){
      var entry=this.getEntry();
      if(entry){
        entry.classList.add('invisible');
      }
    }
    initMenu(){
      this.closeMenu();
      this.initMenuClose();
    } 
    initMenuClose(){
      var this2=this;
      document.addEventListener('click',function(e){
       if(!isElementInside(e.target, this2.elem)){
        this2.closeMenu();
        this2.clearEntry();
        this2.deactivateControl();
        if(this2.item_selected){
          this2.hideEntry();
        }
        this2.displayItems();
       }          
      });
    }
    displayItem(item){
      item.classList.remove('hide');
    }
    displayItems(){
      var items=this.getItems();
      var this2=this;
      items.forEach(function(item){
        this2.displayItem(item);
      });
    }
    getItems(){
      return this.elem.querySelectorAll('.menu-item');
    }
    deactivateControl(){
      this.toggleDownControl();  
    }
    toggleDownControl(){
      var controlIcon=this.getControlIcon();
      if(controlIcon){
        controlIcon.classList.remove('fa-caret-up');
        controlIcon.classList.add('fa-caret-down');
      }
    }
    clearEntry(){
      var entry=this.getEntry();
      if(entry){
        entry.value='';
      }
    }
    closeMenu(){
      this.getMenu().classList.add('hide');
    }
    initControl(){
     var control=this.getControl();
     var this2=this;
     control.addEventListener('click',function(e){
      e.preventDefault();
      this2.showEntry();
      if(this2.getItemsLength()){
       this2.openMenu();
      }
      this2.activateControl();
     }); 
    }
    getItemsLength(){
      return this.getMenu().querySelectorAll('.menu-item').length;
    }
    getControlIcon(){
        return this.elem.querySelector('.control-icon');
    }
    toggleUpControl(){
      var controlIcon=this.getControlIcon();
      controlIcon.classList.remove('fa-caret-down');
      controlIcon.classList.add('fa-caret-up');
    }
    activateControl(){
      this.toggleUpControl();
      var entry=this.getEntry();
      entry.focus();
      if(this.item_selected){
        //this.item_selected=false;
        //this.clearMainInput();
        /* this.clearSelectedItem(); */
        this.getEntry().value=this.getSelectedItem().textContent;
      }
    }
    getSelectedItem(){
        return this.elem.querySelector('.selected-item');
    }
    clearSelectedItem(){
      this.getSelectedItem().textContent='';
    }
    clearMainInput(){
      var mainInput=this.getMainInput();
      if(mainInput){
          mainInput.value='';
      }
    }
    getMainInput(){
        return this.elem.querySelector('.main-input');
    }
    openMenu(){
     this.getMenu().classList.remove('hide'); 
    }
    getMenu(){
      return this.elem.querySelector('.menu');  
    }
    showEntry(){
      var entry=this.getEntry();
      entry.classList.remove('invisible');
    }
    getEntry(){
        return this.elem.querySelector('.entry');
    }
    getControl(){
        return this.elem.querySelector('.control');
    }
    init(){
      this.initElem();
      this.initComponents();
      if(this.init_callback){
        this.init_callback(this);  
      }
    }
  }
});