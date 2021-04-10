define(function(){
    return class Toggle{
        constructor(selector){
           this.selector=selector; 
           this.elem=null;
           this.toggleTimeout=null;
           this.toggleCallback=null;
        }

        initElem(){
            if(!this.elem){
                this.elem=document.querySelector(this.selector);
            }
            return this.elem;
        }
        setElem(elem){
            this.elem=elem;
            return this;
        }
        initToggle(){
          var elem=this.elem;
          if(elem){
            var this2=this;
            elem.addEventListener('click',function(e){
                e.preventDefault();
                this2.clearToggleTimeout();         
                this2.toggleTimeout=setTimeout(function(){
                    this2.toggle();
                    this2.changeTarget();
                    if(this2.toggleCallback){
                        this2.toggleCallback(this2);
                    }
                },300);
            });
          }  
        }
        isActive()
        {
            return this.elem.classList.contains('active')?true:false;
        }
        toggle(){
          if(this.isActive()){
            this.elem.classList.remove('active');
          }else{
            this.elem.classList.add('active');
          }  
        }
        changeTarget(){
           var target_selector=this.elem.dataset.target;
           var target=document.querySelector(target_selector);
           if(target){
             if(target.value==0){
                target.value=1;
             }else{
                 target.value=0;
             }
           }
        }
        clearToggleTimeout()
        {
            if(this.toggleTimeout){
                clearTimeout(this.toggleTimeout);
                this.toggleTimeout=null;
            }   
            return this;
        }
        init(){
            this.initElem();
            this.initToggle();
        }
    }
});