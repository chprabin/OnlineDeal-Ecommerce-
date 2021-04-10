define(['request'],function(Request){
 return class AjaxForm{
     constructor(config={}){
         this.form=null;
         this.req=new Request();
         this.data={};
         this.config=config;
         this.sendCallback=null;
         this.beforeSendCallback=null;
         this.errorElements=[];
         this.fileUploadedCallback=null;
     }

     onSend(callback){
        this.sendCallback=callback;
        return this;
     } 
     onBeforeSend(callback){
         this.beforeSendCallback=callback;
         return this;
     }
     onFileUploaded(callback){
        this.fileUploadedCallback=callback;
        return this;
     }

     initForm(){
        if(this.form){
            return;
        } 
        var selector=this.config.selector;
        var form=document.querySelector(selector);
        if(!form || !selector){
            throw new Error('no form found  with '+selector+' selector');
        }
        this.form=form;
        return this;
     }
     addMsg(result, msg){
      var element=document.createElement('div');
      element.classList.add('form-msg');
      if(result){
        element.classList.add('form-success');
      }else{
        element.classList.add('form-fail');
      }
      element.innerHTML=msg;
      var this2=this;
      this.clearMsg();
      setTimeout(function(){
        this2.form.prepend(element);
      },200);
     }
     input(name){
      var elements=this.form.elements;
      for(var i in elements){
        var elem=elements[i];
        if(elem.hasAttribute && elem.hasAttribute('name') && elem.getAttribute('name')==name){
          return elem;
        }
      }
      return null;
     }
     addErrors(errors={}){
      for(var e in errors){
        var input=this.form.querySelector("[name='"+e+"']");
        if(input){
          var errorElement=document.createElement('div');
          errorElement.classList.add('form-error');
          errorElement.innerHTML=errors[e];
          input.insertAdjacentElement('afterEnd',errorElement);
          this.errorElements.push(errorElement);
        }
      }
     }
     initFileUploaders(){
        var file_selectors=this.form.querySelectorAll('.file-selector');
        var this2=this;
        file_selectors.forEach(function(file_selector){
            file_selector.addEventListener('click',function(e){
              e.preventDefault(); 
              var uploader_elem=this2.form.querySelector("[name='"+this.getAttribute('input_name')+"']");
              uploader_elem.onchange=function(e){
                  if(this2.fileUploadedCallback){
                    this2.fileUploadedCallback(this2, uploader_elem);
                  }
              }
              uploader_elem.click();
            });
        });
     }
     setForm(form){
        this.form=form;
        return this;
     }
     callBeforeSendCallback(){
         if(this.beforeSendCallback){
            return this.beforeSendCallback(this);
         }
         return true;
     }
     loadData(){
        var elements=this.form.elements;
        var data={};
        for(var i in elements){
            var elem=elements[i];
            if(elem.hasAttribute && elem.hasAttribute('name')){
                var name=elem.name;
                if(elem.type=='file' && elem.files[0]!=undefined && typeof elem.files[0]=='object'){
                    data[name]=elem.files[0];
                }else if(elem.type!='file'){
                    data[name]=elem.value;
                }
            }
        }
        this.data=data;
     }
     clearMsg(){
        var msgElem=this.form.querySelector('.form-msg');
        if(msgElem){
            msgElem.remove();
        }
     }
     clearErrors(){
        this.errorElements.forEach(function(element){
            element.remove();
        });
     }
     send(url, method, data){
         this.clearMsg();
         this.clearErrors();
         var result=this.req.send(url, method, data);
         if(this.sendCallback){
          this.sendCallback(this, result);
         }
     }
     initSensors(){
      var sensors=this.form.querySelectorAll('.sensor');
      var this2=this;
      sensors.forEach(function(s){
        if(s.type=='select-one'){
          s.addEventListener('change',function(e){
            this2.submit();
          });  
        }else{
          s.addEventListener('click',function(e){
            this2.submit();
          });
        }
      });
     }
     init(){
      this.initForm();
      this.initFileUploaders();   
      this.initSensors();
      var this2=this;
      this.form.onsubmit=function(e){
        e.preventDefault();
        if(this2.callBeforeSendCallback()!==false){
            this2.loadData();
            this2.send(this2.form.action, this2.form.method, this2.data);
        }
      }
     }
     disable(){
       var submitElement=this.form.querySelector("[type='submit']");
       if(submitElement){
        submitElement.disabled=true;
       }  
     }
     enable(){  
        var submitElement=this.form.querySelector("[type='submit']");
        if(submitElement){
            submitElement.disabled=false;
        }
     }
     submit(){
         var submitElement=this.form.querySelector("[type='submit']");
         if(submitElement){
            submitElement.click();
         }
     }
 }
});