define(['selectable-list'],function(SelectableList){
    return class CoupledSelectables{
        constructor(config={}){
            this.config=config;
            this.selectables=[];
            this.elem=null;
            this.addSelectables();
        }

        addSelectables(){
            var elem_selector=this.config.selector;
            var configs=[
                {selector:elem_selector+' .selectable-list:first-child', remote_loading:true,},
                {selector:elem_selector+' .selectable-list:last-child', remote_loading:true},
            ];
            var this2=this;
            configs.forEach(function(config_object){
                this2.selectables.push(new SelectableList(config_object));
            });
        }
        initElem(){
            var elem=document.querySelector(this.config.selector);
            if(!elem){
                throw new Error('no coupled selectables found with '+this.config.selector);
            }
            this.elem=elem;
        }
        initSelectables(){
            this.selectables.forEach(function(s){
                s.init();
            });
        }
        list1(){
            return this.selectables[0];
        }
        list2(){
            return this.selectables[1];
        }
        init(){
            this.initElem();
            this.initSelectables();
        }
    }
});