<template>
<div>
   <h1 class="custom-h1-title">Tasks</h1>
   <button type="button" class="btn btn-primary" @click="newModal()" data-toggle="modal" data-target="#task-form" data-backdrop="static" data-keyboard="false">Add Task <i class="fa fa-plus"></i></button>
   <div class="container-fluid">
      <table class="custom-table table table-striped">
         <thead>
            <tr class="row">
               <th class="col-2">Client</th>
               <th class="col-2">Project</th>
               <th class="col-3">Task</th>
               <th class="col-2">Assigned Users</th>
               <th class="col-2">User Roles Involved</th>
               <th class="col-1">Actions</th>
            </tr>
         </thead>
         <tbody v-if="showRows">
            <tr class="row" v-for="task in tasks.data" :key="task.id" :item="task">
               <td class="col-2" >{{task.client.name}}</td>
               <td class="col-2" >{{task.project.name}}</td>
               <td class="col-3">{{task.name}}</td>
               <td class="col-2"><span v-for="(item,index) in  task.users" :key="item.name">
                  {{item.name}}<span v-if="index+1 < task.users.length">, </span>
                  </span>
               </td>
               <td class="col-2">
                  <span v-for="(item, index) in  task.users" :key="item.name">
                  {{item.pivot.user_role}}<span v-if="index+1 < task.users.length">, </span>
                  </span>
               </td>
               <td class="col-1">
                  <a href="#" data-toggle="modal" data-target="#task-form" @click="editModal(task)"><i class="fa fa-edit"></i></a> / 
                  <a href="#" data-toggle="modal" data-target="#delete-task-form" @click="setTaskId(task.id)"><i class="fa fa-trash"></i></a>
               </td>
            </tr>
         </tbody>
         <caption v-else style="caption-side:bottom;">
            <div class="d-flex flex-column align-items-center justify-content-center">
               <div class="row">
                  <div class="spinner-border text-primary" role="status">
                     <span class="sr-only">Loading...</span>
                  </div>
               </div>
            </div>
         </caption>
      </table>

   </div>
                       <div class="box-footer">
            <pagination :data="tasks"  class="pagination pagination-md no-margin float-right" @pagination-change-page="getResults"></pagination>

            </div>
   <div class="modal" id="task-form">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title" v-show="!editMode">Add Task</h4>
               <h4 class="modal-title" v-show="editMode">Update Task</h4>
               <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form @submit.prevent="editMode ? updateTask() : createTask()">
            <div class="modal-body">
                  <div class="form-group row" :class="{ 'has-error': form.errors.has('client') }">
                     <label for="client" class="col-sm-2 col-form-label">Client</label>
                     <div class="col-sm-10">
                        <v-select v-model="form.client" label="name" :options="client" name="client" id="client" />
                        <has-error :form="form" field="client"></has-error>
                     </div>
                  </div>
                  <div class="form-group row" :class="{ 'has-error': form.errors.has('project') }">
                     <label for="project" class="col-sm-2 col-form-label">Project</label>
                     <div class="col-sm-10">
                        <v-select v-model="form.project" label="name" :options="project" id="project" />
                        <has-error :form="form" field="project"></has-error>
                     </div>
                  </div>
                  <div class="form-group row" :class="{ 'has-error': form.errors.has('name') }">
                     <label for="task" class="col-sm-2 col-form-label">Task</label>
                     <div class="col-sm-10">
                        <input type="text" v-model="form.name" class="form-control" id="task">
                           <has-error :form="form" field="name"></has-error>
                     </div>
                  </div>
                  <div class="form-group row" :class="{ 'has-error': form.errors.has('users') }">
                     <label for="assigned_users" class="col-sm-2 col-form-label">Assigned Users</label>
                     <div class="col-sm-10">
                           <v-select v-model="form.users" multiple label="name" :options="users" id="assigned_users" />
                           <has-error :form="form" field="users"></has-error>
                     </div>
                  </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer ">

               <div class="btn-group col-4 pr-1" role="group">
                  <button type="button" class="btn btn-primary mr-1" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" v-if="editMode">Update</button>
                  <button type="submit" class="btn btn-primary" v-else>Save</button>

               </div>
     
            </div>
            </form>
         </div>
      </div>
   </div>
      <div class="modal" id="delete-task-form">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
               <h4 class="modal-title">Delete Task</h4>
               <button type="button" id="closeModal" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form @submit.prevent="deleteTask()">
            <div class="modal-body">
               Do you want to delete this task?
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <div class="btn-group col-4 pr-1" role="group">
                  <button type="button" class="btn btn-primary mr-1" data-dismiss="modal">No</button>
                  <button type="submit" class="btn btn-primary">Yes</button>
               </div>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>
</template>

<script>
 export default {
    data() {
        return {
            editMode: false,
            tasks: {},
            client: [],
            project: [],
            users: [],
            page: 0,
            taskId: '',
            showRows: false,
            // Create a new form instance
            form: new Form({
                id:'',
                name: '',
                client: '',
                project: '',
                users: ''
            }),
            search: ''
        }
    },
    methods: {
          getResults(page = 1) {
      this.page = page;
      axios.get('api/tasks?page=' + page)
         .then(response => {
            this.tasks = response.data;
         });
   },
         newModal() {
            this.fillSelects();
            this.page = 0;
            this.editMode = false;
            this.form.clear();
            this.form.reset();
          },
         editModal(task) {
            this.fillSelects();
            this.editMode = true;
            this.form.clear();
            this.form.reset();
            // Use Vform fill method
            this.form.id = task.id;
            this.form.fill(task);
         },
         setTaskId(taskId){
            this.taskId = taskId;
         },

         loadTasks(delayTime) {
            const api = axios.create({
                adapter: delayAdapterEnhancer(axios.defaults.adapter)
            });
            api.get('api/tasks', {
                delay: delayTime,
            }).then(response => {
               //  $('#first-li a')[0].click();
                this.tasks = response.data;
                this.showRows = true;
            });
        },
         createTask() {
            this.form.post('api/tasks').then(function(){     
            $('.modal-header button')[0].click();
            vueEvent.$emit('RefreshData');         
            }).catch(function (error) {
              console.log(error);
            });

          },
         updateTask() {
          this.form.put('api/tasks/' +this.form.id).then(() => {
            $('.modal-header button')[0].click();
            vueEvent.$emit('RefreshData');   
          })
          },
         setTaskId(taskId){
            this.taskId = taskId;
         }, 
         deleteTask() {
            // alert(this.taskId);
          this.form.delete('api/tasks/' + this.taskId).then(() => {
               $('.modal-header button')[1].click();
            vueEvent.$emit('RefreshData');   
          })
          }, 
         fillSelects() {
            axios.get('api/get-clients').then(response => this.client = response.data);
            axios.get('api/get-projects').then(response => this.project = response.data);
            axios.get('api/get-users').then(response => this.users = response.data);
        },
        

    },
            		// Our method to GET results from a Laravel endpoint

    created() {

        this.loadTasks(400);
            vueEvent.$on('RefreshData', ()=> {
                this.loadTasks(100);
            });

    }
}
</script>
