define(function(){
    return class List{
        constructor(){
            this.data=[
            ];
        }
        all(){
            return this.data;
        }
        clear(){
            this.data=[];
        }
        first(){
            if(this.data.length > 1){
                return this.data[0];
            }
            return null;
        }
        toJson(){
            return JSON.stringify(this.data);
        }
        remove(obj){
            var index=this.data.indexOf(obj);
            if(index!=-1){
               this.data.splice(index,1);
               return true; 
            }
            return false;
        }
        findByAttributes(attributes={}){
          var data=this.data;
          for(var i in data){
            var found=data[i];
            for(var j in attributes){
                if(found[j]==undefined || found[j]!=attributes[j]){
                    found=false;
                    break;
                }
            }
            if(found){
                return found;
            }
          }
          return found;  
        }
        removeByAttributes(attributes={}){
          var found=this.findByAttributes(attributes);
          if(found){
            var copy=Object.assign({},found);
            this.remove(found);
            return copy;
          }
          return false;  
        }
        add(obj){
            if(!obj.id){
                obj.id=uuid();
            }
            this.data.push(obj);
            return;
        }
        find(obj, ignoreAttributes=[]){
            var data=this.data;
            for(var i in data){
                var d=data[i];
                if(compareObjects(obj, d, ignoreAttributes)){
                  return d;  
                }
            }
            return false;
        }
        addIfNotExists(obj, attributes={}){
          var found=this.findByAttributes(attributes);
          if(!found){
           return this.add(obj);
          }  
          return false;
        }
    }
});