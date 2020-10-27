<template>
    <div class="container">
        <nav class="navbar navbar-expand-sm bg-light">
            <!-- Links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Inventory</a>
                </li>
            </ul>
        </nav>
    
        <h2 class="text-center text-primary py-4 mb-5">Items Management Page</h2>

        <div class="col-12 border p-4">
            <div class="row">
                <div class="col-5">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" v-model="itemname"  placeholder="Enter Item Name and Click Add" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" v-on:click="addItem">Add</button>
                        </div>
                    </div>
                    <select class="form-control" v-model="availableItemsSelectedValues" multiple="multiple">
                        <option v-for="item in items" v-bind:value="item.item_name" :key="item.id">
                            {{ item.item_name }}
                        </option>
                    </select>
                </div>
                <div class="col-2">
                    <button v-on:click="selectItem" class="border border-secondary px-3 d-block mb-2">&gt;</button>
                    <button v-on:click="unselectItem" class="border border-secondary px-3 d-block">&lt;</button>
                </div>
                <div class="col-5">
                    <h4>Selected Items:</h4>
                    <select class="form-control" v-model="selectedItemsSelectedValues" multiple="multiple">
                       <option v-for="item in selectedItems" v-bind:value="item.item_name" :key="item.id">
                           {{ item.item_name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                items:[],
                hasError:true,
                itemname:'',
                iname:'',
                selectedItems: [],
                availableItemsSelectedValues: [],
                selectedItemsSelectedValues: [],
            }
        },
        created(){
            this.fetchItems();
        },
        methods:{
            addItem(){
                let app = this;
                let name = app.itemname.trim();
                if(name != '')
                {
                    let alreadyAdded = this.items.findIndex(function(el) {
                        return el.item_name == name;
                    });

                    if(alreadyAdded != "-1")
                    {
                        alert("Item with name " + name + " is already added to the list.");
                    }
                    else
                    {
                        let alreadyExist = app.selectedItems.findIndex(function(el) {
                            return el.item_name == name;
                        });

                        if(alreadyExist != "-1")
                        {
                            alert("Item with name " + name + " is already added to the list.");
                        }
                        else
                        {
                            axios.post('/api/items/create',{'item_name': name}).then(function(res){
                                if(res.status == 200)
                                {
                                    app.items.push(res.data.items);
                                }
                                else
                                {
                                    alert('Sorry!! Could not save the item.');
                                }

                                app.itemname = '';                                                                                                                                                                                                                                                                                      
                            });
                        }
                    }
                }
            },
            move(name, action) {
                let arrTo = this.items;
                let arrFrom = this.selectedItems;
                let isSelected = false;
                if(action == 'select')
                {
                    arrTo = this.selectedItems;
                    arrFrom = this.items;
                    isSelected = true;
                }

                axios.post('/api/item/move', {'item_name': name, 'isSelected': isSelected}).then(function(res) {
                if(res.status == 200)
                {
                    var index = arrFrom.findIndex(function(el)
                    {
                        return el.item_name == name;
                    });
                        
                    var item = arrFrom[index];
    
                    arrFrom.splice(index, 1);
                    arrTo.push(item);
                }
                else
                {
                    alert('Sorry!! Could not save the item.');
                }
            });

            },
            selectItem() {
                var selected = this.availableItemsSelectedValues.slice(0);
                if(selected.length > 1)
                {
                    alert('Only 1 value is allowed to move at a time.');
                }
                else if(selected.length == 1)
                {
                    for (var i = 0; i < selected.length; ++i)
                    {
                        this.move(selected[i], 'select');
                    }
                }
            },
            unselectItem(){
                var unselected = this.selectedItemsSelectedValues.slice(0);
                if(unselected.length > 1)
                {
                    alert('Only 1 value is allowed to move at a time.');
                }
                else if(unselected.length == 1)
                {
                    for (var i = 0; i < unselected.length; ++i)
                    {
                        this.move(unselected[i], 'unselect');
                    }
                }
            },
            fetchItems(){
                let app = this;
                axios.get('/api/items').then(function(resp){
                    for (var i = 0; i < resp.data.length; ++i)
                    {
                        let allItems = resp.data;
                        if(allItems[i].is_selected)
                        {
                            console.log('asasasasas');
                            app.selectedItems.push(allItems[i]);
                        }
                        else
                        {
                            app.items.push(allItems[i]);
                        }
                    }
                });
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>