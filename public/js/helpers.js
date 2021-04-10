function dropdowns(){
  var dropdowns=document.querySelectorAll('.dropdown');
  dropdowns.forEach(function(d){
    var toggle=d.querySelector('.dropdown-toggle');
    var menu=d.querySelector('.dropdown-menu');
    hideDropdownMenu(d);
    toggle.addEventListener('click',function(e){
        e.preventDefault();
        toggleDropdownMenu(d);
    });
    document.addEventListener('click',function(e){
        if(!isElementInside(e.target, menu) && !isElementInside(e.target, toggle)){
            hideDropdownMenu(d);
        }
    });
    if(d.classList.contains('a-dropdown')){
      var menu_items=menu.querySelectorAll('.menu-item');
      menu_items.forEach(function(menu_item){
       menu_item.addEventListener('click',function(e){
        e.preventDefault();
        var text=e.target.dataset.text;
        toggle.querySelector('.toggle-text').textContent=text;     
        hideDropdownMenu(d);
       });
      });  
    }
  });
}

function isElementInside(clickedElem, element){
    if(clickedElem==element){
        return true;
    }
    if(clickedElem.parentNode){
        return isElementInside(clickedElem.parentNode, element);
    }else{
        return false;
    }
}
function toggleDropdownMenu(d){
   var menu=d.querySelector('.dropdown-menu');
   if(menu.classList.contains('hide')){
    menu.classList.remove('hide');
   }else{
    menu.classList.add('hide');   
   }
}
function hideDropdownMenu(d){
 var menu=d.querySelector('.dropdown-menu');
 menu.classList.add('hide');
}

function uuid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
}
function getObjectLength(object){
   var length=0;
   for(var i in object){
    length++;
   } 
   return length;
}
function getObjectType(object){
    var type=typeof object;
    return type.toLowerCase();
}
function compareObjects(obj1, obj2, ignoreAttributs=[]){
  var obj1length=getObjectLength(obj1); 
  var obj2length=getObjectLength(obj2);
  if(obj1length!=obj2length){
    return false;
  } 
  for(var i in obj1){
    if(ignoreAttributs.includes(i)){
        continue;
    }
    var value1type=getObjectType(obj1[i]);
    var value2type=getObjectType(obj2[i]);
    if(value1type==value2type){
       if(value1type=='object'){
        return compareObjects(obj1[i],obj2[i], ignoreAttributs);
       }else{
        if(obj1[i]!=obj2[i])
         return false;
       } 
    }else{
       return false; 
    }
  }
}

function addAjaxLoader(){
  var wrapper=document.createElement('div');
  wrapper.classList.add('ajax-loader-wrapper');
  var image=document.createElement('img');
  image.src="/images/app/ajax-loader.gif";
  wrapper.append(image);
  document.body.append(wrapper);
}
function removeAjaxLoader(){
  var wrapper=document.querySelector('.ajax-loader-wrapper');
  if(wrapper){
    wrapper.remove();
  }
}

function createComponent(component, config={}, callback){
  require([component],function(Component){
    var instance=new Component(config);
    callback(instance);
  });
}

function create_preview_image(wrapper_selector, file){
  var img=document.createElement('img');
  img.src=URL.createObjectURL(file);
  var wrapper=document.querySelector(wrapper_selector);
  wrapper.innerHTML=null;
  wrapper.append(img);
}

function create_component_modal(component, config={}, callback=null){
  require(['component-modal',component],function(Modal, Component){
    var modal=new Modal();
    modal.set_component(new Component(config));
    if(callback){
     callback(modal);
    }
  });
}

function adjustHeights(selector){
  var $elems=$(selector);
  var max_height=0;
  $elems.each(function(index, elem){
    var $elem=$(elem);
    if($elem.height() > max_height){
      max_height=$elem.height();
    }
  });
  $elems.height(max_height);
}

function flashSuccess(msg,millis=3000){
 var msg_div=document.createElement('div');
 msg_div.classList.add('flash-msg');
 msg_div.classList.add('flash-success');
 var modal_div=document.createElement('div');
 modal_div.classList.add('flash-modal');
 msg_div.innerHTML=msg;
 modal_div.append(msg_div);
 document.body.append(modal_div);
 setTimeout(function(){
  removeFlash();
 },millis); 
}

function flashFail(msg, millis=3000){
  var msg_div=document.createElement('div');
 msg_div.classList.add('flash-msg');
 msg_div.classList.add('flash-fail');
 var modal_div=document.createElement('div');
 modal_div.classList.add('flash-modal');
 msg_div.innerHTML=msg;
 modal_div.append(msg_div);
 document.body.append(modal_div);
 setTimeout(function(){
  removeFlash();
 },millis); 
}

function removeFlash(){
  var flash_modal=document.querySelector('div.flash-modal');
  if(flash_modal){
      flash_modal.remove();
  }
}


function getTimerString(time_diff){
  var days=Math.floor(time_diff/(1000*3600*24));
  var hours=Math.floor((time_diff%(1000*3600*24)) / (1000*3600));
  var minutes=Math.floor((time_diff%(1000*3600) / (1000*60)));
  var seconds=Math.floor((time_diff % (1000*60)) / 1000);
  var out='';
  if(days > 0){
    out+=days+' days ';
  }
  hours=hours >= 10 ?hours:("0"+hours);
  out+=hours+':';
  minutes=minutes >= 10 ?minutes:('0'+minutes);
  out+=minutes+':';
  seconds=seconds >= 10? seconds: ("0"+seconds);
  out+=seconds;
  return out;
}