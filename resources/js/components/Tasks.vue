<template>
<div class="card">
    <div class="card-header">
    <h1 class="custom-h1-title">Tasks</h1>
    </div>
    <div class="card-body" v-if="this.$root.checkImportCSV > 0">
        <notifications group="add-task" classes="add-task-notification"/>
        <notifications group="update-task" classes="update-task-notification"/>
        <notifications group="delete-task" classes="delete-task-notification"/>
        <div class="row">
            <div class="col-lg-6 col-sm-12 custom-add-task-sm">
                <button type="button" class="btn btn-primary col-md-2 col-sm-12 custom-add-task-sm" @click="newModal()" data-toggle="modal" data-target="#task-form" data-backdrop="static" data-keyboard="false">Add Task <i class="fas fa-plus"></i></button>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="clearfix">
                    <div class="d-flex flex-row float-right">
                        <div @click="getListUsers()">
                            <form id="filterByUsers">
                                <v-select @input="filterByUsers()" multiple label="name" v-model="formFilter.listUsers"  :options="listUsers" id="filter_assigned_users" class="select-options" placeholder="Filter Assigned Users">
                                      <span slot="no-options"></span>
                                </v-select>
                            </form>
                        </div>
                        <div>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button id="export_excel" class="dropdown-item custom-dropdown-item" type="button">
                                    <export-excel 
                                        :before-generate="()=>{return exportExcelAll('all')}"
                                        :data   = "json_data"
                                        :fields = "json_fields"
                                        worksheet = "My Worksheet"
                                        name = "tasks.xls"><i class="fas fa-file-excel"></i> XLS
                                    </export-excel> 
                                </button>
                                <button id="export_pdf" class="dropdown-item custom-dropdown-item" type="button" @click="generatePDF('all')"><i class="fas fa-file-pdf"></i> PDF</button>
                                    <button  class="dropdown-item custom-dropdown-item" type="button">
                                    <export-excel
                                        :before-generate="()=>{return exportExcelAll('all')}"
                                        :data   = "json_data"
                                        :fields = "json_fields"
                                        worksheet = "My Worksheet"
                                        type = "csv"
                                        name = "tasks.csv"><i class="fas fa-file-csv"></i> CSV
                                    </export-excel> 
                                </button>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="container-fluid custom-table table-responsive-lg">
            <table class="table table-striped custom-table-sm">
                    <tr class="row">
                        <th class="col-2">Client</th>
                        <th class="col-2">Project</th>
                        <th class="col-3">Task</th>
                        <th class="col-2">Assigned Users</th>
                        <th class="col-2">User Roles Involved</th>
                        <th class="col-1">Actions</th>
                    </tr>
                <tbody v-if="showRows">
                    <tr class="row" v-for="task in tasks.data" :key="task.id" :item="task">
                        <td class="col-2">{{task.client.name}}</td>
                        <td class="col-2">{{task.project.name}}</td>
                        <td class="col-3">{{task.name}}</td>
                        <td class="col-2">
                            <span v-for="(item,index) in  task.users" :key="item.name"> {{item.name}}<span v-if="index+1 < task.users.length">, </span> </span>
                        </td>
                        <td class="col-2">
                            <span v-for="(item, index) in  task.users" :key="item.name"> {{item.pivot.user_role}}<span v-if="index+1 < task.users.length">, </span> </span>
                        </td>
                        <td class="col-1">
                            <a href="#" data-toggle="modal" data-target="#task-form" @click="editModal(task)"><i class="fas fa-edit"></i></a> /
                            <a href="#" data-toggle="modal" data-target="#delete-task-form" @click="setTaskId(task.id)"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                <caption v-else>
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="row">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </caption>
                <caption v-if="emptyData">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="row">
                            <div>
                                <span>No tasks found</span>
                            </div>
                        </div>
                    </div>
                </caption>
            </table>
        </div>
        <div v-if="showRows">
            <pagination :data="tasks" class="pagination pagination-md no-margin float-right" @pagination-change-page="getResults"></pagination>
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
                                <label for="client" class="col-sm-3 col-form-label">Client</label>
                                <div class="col-sm-9">
                                    <v-select v-model="form.client" label="name" :options="client" name="client" id="client" />
                                    <has-error :form="form" field="client"></has-error>
                                </div>
                            </div>
                            <div class="form-group row" :class="{ 'has-error': form.errors.has('project') }">
                                <label for="project" class="col-sm-3 col-form-label">Project</label>
                                <div class="col-sm-9">
                                    <v-select v-model="form.project" label="name" :options="project" id="project" />
                                    <has-error :form="form" field="project"></has-error>
                                </div>
                            </div>
                            <div class="form-group row" :class="{ 'has-error': form.errors.has('name') }">
                                <label for="task" class="col-sm-3 col-form-label">Task</label>
                                <div class="col-sm-9">
                                    <input type="text" v-model="form.name" class="form-control" id="task" />
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="form-group row" :class="{ 'has-error': form.errors.has('users') }">
                                <label for="assigned_users" class="col-sm-3 col-form-label">Assigned Users</label>
                                <div class="col-sm-9">
                                    <v-select v-model="form.users" multiple label="name" :options="users" id="assigned_users" />
                                    <has-error :form="form" field="users"></has-error>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary btn-left" data-dismiss="modal">Close</button>
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
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary btn-left" data-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div v-else><default-message></default-message></div>
</div>
</template>
<script>
import { jsPDF } from "jspdf";
import 'jspdf-autotable';
export default {

    data() {
        return {
            editMode: false,
            tasks: '',
            client: [],
            project: [],
            users: [],
            listUsers: [],
            filterListUsers: [],
            page: 1,
            taskId: '',
            showRows: false,
            notificationType: 0,
            emptyData: false,

            // Create a new form instance
            form: new Form({
                id: '',
                name: '',
                client: '',
                project: '',
                users: ''
            }),
            formFilter: new Form({
                listUsers: ''
            }),

            // Export Excel
            json_fields: {
                'Client': 'client.name',
                'Project': 'project.name',
                'Task': 'name',
                'Assigned Users': {
                    field: 'users',
                    callback: (users) => {
                        var arr = [];
                        users.forEach((user) => {
                            arr.push(user.name);
                        });
                        return arr.join(', ');
                    }
                },
                'User Roles Involved': {
                    field: 'users',
                    callback: (users) => {
                        var arr = [];
                        users.forEach((user) => {
                            arr.push(user.pivot.user_role);
                        });
                        return arr.join(', ');
                    }
                },
            },
            json_data: null,
            json_meta: [
                [{
                    'key': 'charset',
                    'value': 'utf-8'
                }]
            ],
            pdf_data: null
        }
    },
    methods: {
        // Method to GET results from a Laravel endpoint
        getResults(page = 1) {
            this.page = page;
            this.loadTasks(0, this.filterListUsers, page);
        },
        newModal() {
            this.fillSelects();
            this.page = 1;
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
        setTaskId(taskId) {
            this.taskId = taskId;
        },
        async loadTasks(delayTime, filterListUsers, page) {
            const api = axios.create({
                adapter: delayAdapterEnhancer(axios.defaults.adapter)
            });
            await api.get('api/tasks', {
                params: {
                    filterListUsers: filterListUsers,
                    page: page
                },
                delay: delayTime,
            }).then(response => {
                this.tasks = response.data;
                this.json_data = this.tasks.data;
                this.pdf_data = this.tasks.data;
                this.showRows = true;
                this.emptyData = false;
                if(this.tasks.data.length == 0) {
                    this.emptyData = true;
                }
            });
            // Get notification after the tasks are loaded on refreshData 
            this.getNotificationType();
        },
        async createTask() {
            await this.form.post('api/tasks').then(() => {
                $('.modal-header button')[0].click();
                this.filterListUsers = [];
                this.formFilter.listUsers=[];
                this.refreshData();
            });
            this.notificationType = 1;
        },
        async updateTask() {
            await this.form.put('api/tasks/' + this.form.id).then(() => {
                $('.modal-header button')[0].click();
                this.refreshData();
            });
            this.notificationType = 2;
        },
        setTaskId(taskId) {
            this.taskId = taskId;
        },
        async deleteTask() {
            await this.form.delete('api/tasks/' + this.taskId, { 
                data: { 
                    filterListUsers: this.filterListUsers
                }
            }).then((response) => {
                var paginate = response.data.paginate;
                if(Number.isInteger(paginate)) {
                    this.page = 1;
                }
                $('.modal-header button')[1].click();
                this.refreshData();
            })
            this.notificationType = 3;
        },
        fillSelects() {
            axios.get('api/get-clients').then(response => this.client = response.data);
            axios.get('api/get-projects').then(response => this.project = response.data);
            axios.get('api/get-users').then(response => this.users = response.data);
        },
        getListUsers() {
            axios.get('api/get-users').then(response => this.listUsers = response.data);
        },
        filterByUsers() {
            this.showRows = false;
            this.emptyData = false;
            this.page = 1;
            let filterListUsers = [];
            let selectedUsers = this.formFilter.listUsers;
            for (let i = 0; i < selectedUsers.length; i++) {
                filterListUsers.push(selectedUsers[i]['id']);
            }
            if (selectedUsers.length > 0) {
                this.filterListUsers = filterListUsers;
            } else {
                this.filterListUsers = [];
            }
            this.loadTasks(150, this.filterListUsers, 1);
        },
        async exportExcelAll(all) {
            await axios.get('api/tasks', {
                params: {
                    filterListUsers: this.filterListUsers,
                    page: all
                }
            }).then(response => this.json_data = response.data);
        },
        getNotificationType() {
            switch (this.notificationType) {
                case 1:
                    this.$notify({
                        group: 'add-task',
                        title: 'Notification',
                        text: 'Task created',
                        duration: 2000,
                    });
                    break;
                case 2:
                    this.$notify({
                        group: 'update-task',
                        title: 'Notification',
                        text: 'Task updated',
                        duration: 2000,
                    });
                    break;
                case 3:
                    this.$notify({
                        group: 'delete-task',
                        title: 'Notification',
                        text: 'Task deleted',
                        duration: 2000,
                    });
                    break;
                default:
            }
            this.notificationType = 0;
        },
        refreshData(){
            this.loadTasks(0, this.filterListUsers, this.page);
        },
        dataToPDF(tasks) {
            var arr = [];
            var tasks = tasks;
            var clientName = '';
            var projectName = '';
            var taskName = '';
            var users = '';  
            
            for (let i = 0; i < tasks.length; i++) {
                clientName = tasks[i]['client']['name'];
                projectName = tasks[i]['project']['name'];
                taskName = tasks[i]['name'];
                var users = function () {
                            users = tasks[i]['users'];
                            var arr = [];
                            users.forEach((user) => {
                                arr.push(user['name']);
                            });
                         return arr.join(', ');
                        }
                var userRoles = function () {
                        users = tasks[i]['users'];
                        console.log(userRoles);
                        var arr = [];
                        users.forEach((users) => {
                            arr.push(users['pivot']['user_role']);
                        });
                        return arr.join(', ');
                    }        
                arr.push([clientName, projectName, taskName, users(), userRoles()]);
            }
            return arr;
        },
        async generatePDF(all){
            await axios.get('api/tasks', {
                params: {
                    filterListUsers: this.filterListUsers,
                    page: all
                }
            }).then(response => this.pdf_data = response.data);
            var doc = new jsPDF('p', 'pt', 'a4');
            doc.autoTable({
            head: [['Client', 'Project', 'Task', 'Assigned Users', 'User Roles Involved']],
            body: this.dataToPDF(this.pdf_data),
                headStyles: {
                    fillColor: '#192841',
                    textColor: '#ffffff',
                },
            })
            doc.save('tasks.pdf')
        }
    },
    created() {
        this.loadTasks(150, this.filterListUsers, this.page);
    },
    mounted() {

    }
}
</script>
