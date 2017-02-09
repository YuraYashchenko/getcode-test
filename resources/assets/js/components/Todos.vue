<template>
    <div>
        <div class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Task</h4>
              </div>
              <div class="modal-body">
                <p>
                  <textarea name="name" rows="3" class="form-control" v-model="newName"></textarea>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="update">Save changes</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <label for="todo"></label>

        <div class="form-group">
            <label for="todo">Create Task:</label>
            <input type="text" name="title" class="form-control" v-model="todo">
        </div>

        <div v-if="error" v-text="error.name[0]" class="alert alert-danger"></div>

        <button class="btn btn-success btn-block" @click.prevent="create">Create</button>

        <h1>Tasks: </h1>
        <ul class="list-group">
            <li class="list-item"   
                v-for="todo in todos">
                <div class="row">
                    <div class="col-md-1">
                        <span @click="move(todo, 'up')" v-show="! upArrow(todo.order)"><i class="fa fa-angle-up" aria-hidden="true"></i></span>
                        <span @click="move(todo, 'down')" 
                            v-show="! downArrow(todo.order)"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                    </div>

                    <div class="col-md-11">
                        <h3 v-text="todo.name"
                            :class="{'finished': finished(todo.progress), 'in-progress': inProgress(todo.progress)}"></h3><span 
                            v-show="inProgress(todo.progress)">(In Progress)</span>
               
                        <button class="pull-right btn btn-primary btn-sm" 
                                :disabled="todo.progress === 'finished'"
                                @click="finish(todo)">Finish</button>

                        <button class="pull-right btn btn-primary btn-sm" 
                                :disabled="inProgress(todo.progress)"  
                                @click="start(todo)">Start</button>
               
                        <button class="btn btn-success btn-sm" 
                                id="edit-btn" 
                                @click="edit(todo)">Edit</button>
               
                        <button class="btn btn-danger btn-sm" 
                                @click="destroy(todo)">Delete</button>
                    </div>
                </div>
                
                
            </li>
        </ul>
    </div>
</template>

<script>
    export default {

        data() {
            return { 
                todos: [], 
                error: false, 
                todo: '',
                newName: '',
                first: false
            }
        },

        created() {
            this.getToDos();
            this.setFirst()
        },

        methods: {
            setFirst() {
                axios.post('api/todos').then(response => this.first = response.data.shift().order);
            },

            create() {
                let todo = {
                    'name': this.todo
                };

                axios.post('/todo', todo)
                        .then(({data}) => { 
                            this.getToDos();
                            this.todo = '';
                            this.setFirst();
                        })
                        .catch((error) => {
                            this.error = error.response.data
                            this.hideError();

                        });
                this.setFirst()
            },

            edit(todo) {
                this.currentTodo = todo;
                $('.modal').modal('show');
                this.newName = todo.name;
            },

            update() {
                let id = this.currentTodo.id;
                let todo = {
                    'name': this.newName
                };
                axios.put(`/todo/${id}`, todo)
                        .then(() => { 
                            this.currentTodo.name = this.newName;
                            this.hideForm();
                        })
                        .catch((error) => { 
                            this.error = error.response.data;
                            this.hideForm();

                            this.hideError();
                        });
            },

            destroy(todo) {
                let index = this.todos.indexOf(todo);
              

                axios.delete(`/todo/${todo.id}`)
                    .then(() => this.todos.splice(index, 1));

                this.setFirst();
            },

            setProgress(todo, progress) {
                todo.progress = progress;
                let newProgress = {
                    'progress': progress,
                    'todoId': todo.id
                }

                axios.post(`/progress`, newProgress);
            },

            start(todo) {
                this.setProgress(todo, 'in_progress');
            },

             inProgress(progress) {
                return progress === 'in_progress';
            },

            finished(progress) {
                return progress === 'finished' & progress !== 'strat';
            },

            finish(todo) {
                this.setProgress(todo, 'finished');
            },

            move(todo, direction) {
                let data = {
                    'id': todo.id,
                    'direction': direction
                };

                axios.post('/move', data)
                    .then(() => this.getToDos());
            },

            hideForm() {
                $('.modal').modal('hide');
            },

            downArrow(order) {
                return order == 0;
            },

            upArrow(order) {
                return order == this.first;
            },
  
            hideError() {
                setTimeout(() => this.error = false, 1000);
            },

            getToDos() {
                axios.post('api/todos').then((response) => {
                    this.todos = response.data;
                });
            }
        },
    }
</script>

<style>
    ul > li {
        list-style-type: none;
    }

    li button {
        margin-right: 1em;
    }

    #finish {
        margin-right: 1em;
    }

    span {
        display: block;
    }

    .in-progress {
        font-style: italic;
        color: green;
    }

    .finished {
        text-decoration: line-through;
    }

    span > i:hover {
        cursor: pointer;
    }

    li {
        border: 1px solid #cecece;
        padding: 1em;
        margin: 1em;
    }

    #edit-btn {
        width: 58.8px;
    }
</style>