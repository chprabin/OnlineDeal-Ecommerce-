    /* function buildUrlWithParams(pattern='',params={}){
      var patternSections=pattern.split("/");
      var qsParams=[];
      var url='';
      patternSections.forEach(function(section){
        if(section.includes('{') && section.includes('}')){
         var paramName=section.replace('{','').replace('}','');
         var paramValue=params[paramName];
         url+=paramValue+'/';
        }else{
          url+=section+'/';
          if(params[section])
           qsParams.push({name:section,value:params[section]});
        }
      });
      url=url.substring(0,url.length-1);
      var qsParamsLength=qsParams.length;
      if(qsParamsLength>0){
        url+='?';
        qsParams.forEach(function(obj){
          url+=obj.name+"="+obj.value+"&";
        });
        url=url.substr(0,url.lastIndexOf('&'));
      }
      return url;
     } */
     function getObjectLength(obj){
      if(obj==null || obj==undefined)
       return false;
      var index=0; 
      for(var i in obj){
          index++;
      }
      return index;
     }
    function getObjectType(obj){
      var objtype=typeof obj;
      return objtype.toLowerCase();
     }
    function compareObjects(obj1,obj2,ignoreAttributes=[]){
      var obj1Length=getObjectLength(obj1);
      var obj2Length=getObjectLength(obj2);
      if(obj1Length!=obj2Length)
       return false; 
      if(getObjectLength(obj1)!==getObjectLength(obj2))
       return false;
      for(var i in obj1)
      {
        if(ignoreAttributes.includes(i))
         continue;
        var firstValueType=getObjectType(obj1[i]);
        var secondValueType=getObjectType(obj2[i]);
        if(firstValueType==secondValueType){
          if(firstValueType=='object')
          {
              return compareObjects(obj1[i],obj2[i]);
          }else{
              if(obj1[i]!=obj2[i])
              return false;
          } 
        }else{
            return false;
        }
      } 
      return true; 
     }
   function uuid() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    }

    function isElementInside(clickedElem,elem){ 
      if(clickedElem==elem){
          return true;
      } 
      if(clickedElem.parentNode){
          return isElementInside(clickedElem.parentNode,elem);
      }else{
          return false;
      } 
    }
    
    function dropdowns(selector){
      selector=selector?selector:'.dropdown';
      var dropdowns=document.querySelectorAll(selector);
      dropdowns.forEach(function(dropdown){
       var menu=dropdown.querySelector('.dropdown-menu');
       var dropdownToggle=dropdown.querySelector('.dropdown-toggle');
       closeDropdown(menu);
       dropdownToggle.addEventListener('click',function(e){
        e.preventDefault();
        toggleDropdown(menu);
       });
       document.addEventListener('click',function(e){
        if(!isElementInside(e.target,menu) && !isElementInside(e.target,dropdownToggle)){
          closeDropdown(menu);
        }        
       });
      });
     }

     function aDropdowns(selector){
      selector=selector?selector:'.a-dropdown';
      var dropdowns=document.querySelectorAll(selector);
      dropdowns.forEach(function(dropdown){
        var dropdownToggle=dropdown.querySelector('.dropdown-toggle');
        var menu=dropdown.querySelector('.dropdown-menu');
        closeDropdown(menu);
        dropdownToggle.addEventListener('click',function(e){
          e.preventDefault();
          toggleDropdown(menu);
        });  
        var toggleTextElem=dropdownToggle.querySelector('.toggle-text'); 
        var menuItems=dropdown.querySelectorAll('.menu-item');
        menuItems.forEach(function(menuItem){
          menuItem.addEventListener('click',function(e){
            e.preventDefault();
            toggleTextElem.textContent=menuItem.dataset.text;
          });
        });
        document.addEventListener('click',function(e){
          if(!isElementInside(e.target,dropdownToggle)){
           closeDropdown(menu);
          }
        });
      });
    }


     function toggleDropdown(menu){
      if(menu.classList.contains('hide')){
        menu.classList.remove('hide');
      }else{
        closeDropdown(menu);
      }
     }
     function closeDropdown(menu){
      menu.classList.add('hide');
     }
     function addAjaxLoader(){
      var ajaxLoaderWrapper=document.createElement('div');
      ajaxLoaderWrapper.classList.add('ajax-loader-wrapper');
      var ajaxLoaderImage=document.createElement('img');
      ajaxLoaderImage.src='/images/app/ajax-loader.gif';
      ajaxLoaderWrapper.append(ajaxLoaderImage);
      document.body.append(ajaxLoaderWrapper);
     }
     function removeAjaxLoader(){
     var elem=document.querySelector('.ajax-loader-wrapper');
     if(elem)
      elem.remove();
     }

     function changeHistory(obj,title,url,onPopState){
       history.pushState(obj,title,url);
       window.onpopstate=function(event){ 
         if(onPopState && typeof onPopState=='function'){
          onPopState(event);
         }
       }
     }
     
     
     function createForm(selector,callback){
      require(['ajax-form'],function(AjaxForm){
       var form=new AjaxForm(selector);
       if(callback && typeof callback=='function')
        callback(form);  
      });      
     }

     function createGalleryModal(config={},callback){
      require(['gallery_modal'],function(GalleryModal){
        var modal=new GalleryModal(config);
        if(callback && typeof callback=='function')
         callback(modal);
      });
     }

     function flashError(msg='',milis=3000){
      var errorDiv=document.createElement('div');
      errorDiv.classList.add('flash-msg');
      errorDiv.classList.add('flash-error');
      var modal=document.createElement('div');
      modal.classList.add('flash-modal');
      errorDiv.innerHTML=msg;
      modal.append(errorDiv);
      document.body.append(modal);      
      setTimeout(function(){
        removeFlashMsg();
      },milis);
     }
     function flashSuccess(msg='',milis=3000){
      var errorDiv=document.createElement('div');
      errorDiv.classList.add('flash-msg');
      errorDiv.classList.add('flash-success');
      var modal=document.createElement('div');
      modal.classList.add('flash-modal');
      errorDiv.innerHTML=msg;
      modal.append(errorDiv);
      document.body.append(modal);      
      setTimeout(function(){
        removeFlashMsg();
      },milis);
     }

     function removeFlashMsg(){
      var flashModal=document.querySelector('.flash-modal');
      flashModal.remove();
     }

     function createAjaxForm(selector,callback){
       require(['ajax-form'],function(AjaxForm) {
        var form=new AjaxForm(selector);
        if(callback && typeof callback=='function')
         callback(form);
       });
     }
     
     function createPreviewImage(wrapperElementSelector, imageFile){
       if(['image/jpeg','image/jpg','image/png'].indexOf(imageFile.type)>-1){
        var wrappereElement=document.querySelector(wrapperElementSelector);
        if(wrappereElement){
          var imgElem=document.createElement('img');
          imgElem.src=URL.createObjectURL(imageFile);
          wrappereElement.innerHTML=null;
          wrappereElement.append(imgElem);
        }
       }
     }

     function createSelectableList(config={},callback){
       require(['selectable_list'],function(SelectableList){
        var list=new SelectableList(config);
        if(callback && typeof callback=='function')
         callback(list);
       });
     }

     function createDataList(config={},callback){
        require(['data-list'],function(DataList){
          var dl=new DataList(config);
          if(callback && typeof callback=='function')
           callback(dl);
        });
     }
     
     function createComponent(component,config={},callback){
      require([component],function(Component){
        var c=new Component(config);
        if(callback && typeof callback=='function'){
          callback(c);
        }
      });
     }

     function activateBooleanCheckboxes(){
       var boxes=document.querySelectorAll("input[type='checkbox'].boolean");
       boxes.forEach((input)=>{
        if(!input.checked){
          input.value=0;
        }
        input.onchange=function(e){
          if(this.checked){
            this.value=1;
          }else{
            this.value=0;
          }
        }
       });
     }

     function createComponentModal(component,config={},callback){
      require(['component-modal',component],function(Modal,Component){
        var modal=new Modal();
        modal.setComponent(new Component(config));
        if(callback && typeof callback=='function')
         callback(modal);
      });
     }
     

     function adjustHeights(selector){
       var $items=$(selector);
       var maxHeight=0;
       $items.each(function(index,elem){
        var $elem=$(elem);
        if($elem.height() > maxHeight){
          maxHeight=$elem.height();
        }
       });
       $items.height(maxHeight);
     }