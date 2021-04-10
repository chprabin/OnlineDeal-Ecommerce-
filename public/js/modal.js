define(['request'],function(Request){
    return class Modal{
        constructor(){
         this.req=new Request();
         this.id=uuid();
         this.isOpen=false;
         this.close_callback=null;
         this.done_callback=null;
         this.opening_timeout=null;            
        }
        on_close(callback){
            this.close_callback=callback;
            return this;
        }
        on_done(callback){
            this.done_callback=callback;
            return this;
        }

        open(url, data={}, callback=null){
          clearTimeout(this.opening_timeout);
          this.opening_timeout=null;
          var this2=this;
          this.opening_timeout=setTimeout(function(){
            var res=this2.req.get(url, data);
            if(res.view){
                this2.addModal(res.view);
            }
            if(callback){
                callback(this2);
            }
          },500);  
        }
        addModal(view){
            var modal_wrapper=this.createWrapper();
            $(modal_wrapper).html(view);
            $('body').append(modal_wrapper);
            this.isOpen=true;
            this.init_close();
            this.init_done();
        }
        init_done(){
            var done_elem=this.getDoneElem();
            if(done_elem){
                var this2=this;
                done_elem.addEventListener('click',function(e){
                   e.preventDefault(); 
                   if(this2.done_callback){
                    this2.done_callback(this2);
                   } 
                   this2.close();
                });
            }
        }
        close(){
            var wrapper=this.getWrapper();
            this.isOpen=false;
            if(wrapper){
                wrapper.remove();
                if(this.close_callback){
                    this.close_callback(this);
                }
            }
        }
        getDoneElem(){
            return this.getWrapper().querySelector('.modal-done');
        }
        getCloseElem(){
            return this.getWrapper().querySelector('.modal-close');
        }
        getWrapper(){
            return document.getElementById(this.id);
        }
        get_modal(){
            return this.getWrapper().querySelector('.modal');
        }
        init_close(){
            var done_elem=this.getDoneElem();
            var close_elem=this.getCloseElem();
            var modal=this.get_modal();
            var this2=this;
            document.addEventListener('click',function(e){
              var t=e.target;
              if(this2.isOpen){
                  if(isElementInside(t,this2.getWrapper()) && 
                  (!isElementInside(t,this2.get_modal()))){
                      this2.close();  
                  }
              }
            });
        }
        createWrapper(){
            var wrapper=document.createElement('div');
            wrapper.classList.add('modal-wrapper');
            wrapper.id=this.id;
            return wrapper;
        }
    }
});