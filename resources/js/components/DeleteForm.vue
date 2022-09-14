<template>
<div>
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
                            <button type="button" class="btn btn-primary btn-left" data-dismiss="modal">No</button>
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

        };
    },
    methods: {
            async deleteTask() {
                await this.form.delete('api/tasks/' + this.taskId).then(() => {
                $('.modal-header button')[1].click();
                vueEvent.$emit('RefreshData');
            })
            this.notificationType = 3;
        },
    },
    created() {

    },
    mounted() {
    }
};
</script>