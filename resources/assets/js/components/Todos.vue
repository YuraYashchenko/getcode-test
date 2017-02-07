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
            <textarea name="title" id="todo" class="form-control" rows="1" v-model="todo"></textarea>
        </div>

        <div v-if="error" v-text="error.name[0]" class="alert alert-danger"></div>

        <button class="btn btn-success btn-block" @click.prevent="create">Create</button>

        <h1>Tasks: </h1>
        <ul class="list-group">
            <li class="list-item"  
                :class="{'in-progress': inProgress(todo.progress), 'finished': finished(todo.progress)}" 
                v-for="todo in todos">
                <h3 v-text="todo.name"></h3><span 
                    v-show="inProgress(todo.progress)">(In Progress)</span>
       
                <button class="pull-right btn btn-primary btn-sm" 
                        :disabled="todo.progress === 'finished'"
                        @click="finish(todo)">Finish</button>

                <button class="pull-right btn btn-primary btn-sm" 
                        :disabled="inProgress(todo.progress)"  
                        @click="start(todo)">Start</button>
       
                <button class="btn btn-success btn-sm" 
                        @click="edit(todo)">Edit</button>
       
                <button class="btn btn-danger btn-sm" 
                        @click="destroy(todo)">Delete</button>
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
                newName: ''
            }
        },

        created() {
            axios.post('api/todos').then(({data}) => this.todos = data);
        },

        methods: {
            create() {
                let todo = {
                    'name': this.todo
                };

                axios.post('/todo', todo)
                        .then(({data}) => { 
                            this.todos.push(data);
                            this.todo = '';
                        })
                        .catch((error) => {
                            this.error = error.response.data
                            this.hideError();

                        });
            },

            edit(todo) {
                console.log(todo);
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
                this.todos.splice(index, 1);

                axios.delete(`/todo/${todo.id}`);
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

            hideForm() {
                $('.modal').modal('hide');
            },

            hideError() {
                setTimeout(() => this.error = false, 1000);
            }
        },
    }
</script>

<style>
    ul > li {
        list-style-type: none;
    }
    li > button{
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
</style>