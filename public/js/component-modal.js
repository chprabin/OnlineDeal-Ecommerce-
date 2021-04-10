define(['modal'],function(Modal){
    return class ComponentModal extends Modal{
        constructor(){
            super();
            this.component=null;
        }
        set_component(component){
            this.component=component;
            return this;
        }
        init(){
            this.component.init();
            return this;
        }
    }
})