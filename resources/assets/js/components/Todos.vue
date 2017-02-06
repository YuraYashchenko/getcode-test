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
                  <textarea name="name" cols="30" rows="10" class="form-control" v-model="newName"></textarea>
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
            <textarea name="title" id="todo" class="form-control" cols="15" rows="5" v-model="todo"></textarea>
        </div>

        <div v-if="error" v-text="error.name[0]" class="alert alert-danger"></div>

        <button class="btn btn-success btn-block" @click.prevent="create">Create</button>

        <h1>Tasks: </h1>
        <ul class="list-group">
            <li class="list-item" v-for="todo in todos">
                <h3 v-text="todo.name"></h3>
                <button class="btn btn-success" @click="edit(todo.id)">Edit</button>
                <button class="btn btn-danger" @click="destroy(todo.id)">Delete</button>
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
            axios.post('api/todos').then(response => this.todos = response.data);
        },

        methods: {
            create() {
                let todo = {
                    'name': this.todo
                };

                axios.post('/todo', todo)
                        .then((response) => { 
                            this.todos.push(response.data);
                            this.todo = '';
                        })
                        .catch((error) => this.error = error.response.data);
            },

            edit(id) {
                let todo = this.findTodoById(id);
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
                        .then((response) => { 
                            let todo = this.findTodoById(id);
                            todo.name = this.newName;
                            this.hideForm();
                        })
                        .catch((error) => { 
                            this.error = error.response.data;
                            this.hideForm();
                        });
            },

            destroy(id) {
                let todo = this.findTodoById(id);
                let index = this.todos.indexOf(todo);
                this.todos.splice(index, 1);

                axios.delete(`/todo/${todo.id}`);
            },

            findTodoById(id) {
               return this.todos.find(todo => todo.id === id)
            },

            hideForm() {
                $('.modal').modal('hide');
            }
        },
    }
</script>

<style>
    ul > li {
        list-style-type: none;
    }
</style>