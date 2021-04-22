<template>
    <div class="card">
        <div class ="card-header">
            <h1 class="custom-h1-title">Import CSV</h1>
        </div>
        <div class="d-flex flex-row card-body">   
            <div v-if="showMessage==false">
                <form @submit.prevent="saveCSVtoDb()">
                    <div class="form-group " :class="{ 'has-error': form.errors.has('file_csv','file_type') }">
                    <label for="file_csv">Select csv file:</label>
                    <input type="file" id="file_csv" @change="getCSVFile()"><br>
                    <has-error :form="form" field="file_csv"></has-error>
                    <has-error :form="form" field="file_type"></has-error>
                    <button class="btn btn-primary col-lg-3 col-sm-12" type="submit" style="margin-top:3px;">Import</button>
                    </div>
                </form>
            </div>
        <div v-else >The csv file is imported. Please click on <b>Tasks</b> from menu above.</div>
        <div v-if="showLoading">Loading ...  </div>
        <div v-if="showLoading" class="pl-2">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
export default {
     data() {
        return {
            showMessage: false,
            showLoading: false,
            // Create a new form instance
            form: new Form({
                file_csv: '',
                file_ext: '',
                file_type: ''
            }),
        }
    },
    methods: {
        getCSVFile() {
            var reader = new FileReader();
            var file_csv = $('#file_csv').prop('files')[0];
            var file_ext = file_csv.name.split('.')[1];
            this.form.file_ext = file_ext;
            reader.readAsText(file_csv);
            reader.onloadend = (e) => {
                this.form.file_csv = e.target.result;
            }
        },
        async saveCSVtoDb() { 
            this.showLoading = true;
            await this.form.post('api/save-csv-to-db').then(function() {
            }).catch(function(error) {
            });
            this.showLoading = false;
            if(!this.form.errors.errors.hasOwnProperty('file_csv') && !this.form.errors.errors.hasOwnProperty('file_type')) {
                this.showLoading = false;
                this.showMessage = true;
                app.checkImportCSV = 1;
            }
        }
    },
}
</script>
