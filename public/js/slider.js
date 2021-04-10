define(function(){
    return class Slider{
        constructor(config={}){
            this.config=config;
            this.slider_wrapper=null;
            this.data_load_callback=null;
            this.current_page=1;
            this.rotation_timeout=null;
        }
        initElem(){
          this.slider_wrapper=$(this.config.selector);
          if(!this.slider_wrapper){
            throw new Error('no slider found with '+this.config.selector+' selector');
          }  
        }
        getGroups(){
            return this.slider_wrapper.find('.group');
        }
        getSlider(){
            return this.slider_wrapper.find('.slider');
        }
        initComponentsSizes(){
            var groups=this.getGroups();
            var slider=this.getSlider();
            var wrapper_width=this.slider_wrapper.width();
            groups.css({width:wrapper_width+'px'});
            slider.css({width:(wrapper_width*groups.length)+'px'});
        }
        initRightRotator(){
            var right_rotator=this.slider_wrapper.find('.right-rotator');
            if(right_rotator){
              var this2=this;
              right_rotator.click(function(e){
                e.preventDefault();
                var should_rotate=false;
                this2.clearRotationTimeout();
                if(this2.data_load_callback && this2.getGroupsCount()==this2.current_page 
                && this2.loadData()==true){
                   should_rotate=true; 
                }else if(this2.current_page < this2.getGroupsCount()){
                    should_rotate=true;
                }
                if(should_rotate){
                    this2.rotation_timeout=setTimeout(function(){
                        this2.rotateRight();
                        this2.current_page++;
                    },350);
                }
              });      
            }
        }
        rotateRight(){
         var slider=this.getSlider();
         var slider_margin_left=slider.css('margin-left');
         slider_margin_left=parseFloat(slider_margin_left.substr(0, slider_margin_left.indexOf('px')));
         var wrapper_width=this.slider_wrapper.width();
         slider_margin_left-=wrapper_width;
         slider.animate({marginLeft:slider_margin_left+'px'});
        }
        on_data_load(callback){
            this.data_load_callback=callback;
            return this;
        }

        getGroupsCount(){
            return this.getGroups().length;
        }
        loadData(){
            return this.data_load_callback(this);
        }
        extend(view){
          var slider=this.getSlider();
          slider.append(view);
          this.initComponentsSizes();  
        }
        initLeftRotator(){
           var left_rotator=this.slider_wrapper.find('.left-rotator');
           if(left_rotator){
            var this2=this;
            left_rotator.click(function(e){
                e.preventDefault();
                this2.clearRotationTimeout();
                if(this2.current_page > 1){
                  this2.rotation_timeout=setTimeout(function(){
                     this2.rotateLeft();
                     this2.current_page--; 
                  },350);  
                }
            });
           } 
        }
        rotateLeft(){
            var slider=this.getSlider();
            var slider_margin_left=slider.css('margin-left');
            slider_margin_left=parseFloat(slider_margin_left.substr(0, slider_margin_left.indexOf('px')));
            var wrapper_width=this.slider_wrapper.width();
            slider_margin_left+=wrapper_width;
            slider.animate({marginLeft:slider_margin_left+'px'});
        }
        clearRotationTimeout(){
           clearTimeout(this.rotation_timeout);
           this.rotation_timeout=null; 
        }
        init(){
            this.initElem();
            this.initComponentsSizes();
            this.initRightRotator();
            this.initLeftRotator();
        }
    }
});