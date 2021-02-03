<template>
    <div>
        <h1 class="custom-h1-title">Import CSV</h1>
        <div>
            <form @submit.prevent="saveCSVtoDb()">
                <div class="form-group " :class="{ 'has-error': form.errors.has('file_csv','file_type') }">
                <label for="file_csv">Select csv file:</label>
                <input type="file" id="file_csv" @change="getCSVFile()"><br>
                <has-error :form="form" field="file_csv"></has-error>
                <has-error :form="form" field="file_type"></has-error>
                <button class="btn btn-primary" type="submit">Import</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
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
    }    
    ,
    saveCSVtoDb(){
        this.form.post('api/save-csv-to-db').then(function(){     
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>
