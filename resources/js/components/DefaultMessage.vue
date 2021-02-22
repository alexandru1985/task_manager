<template>
    <div class="card-body">
        <p>
            First you must download a csv file from <a href="#" @click="downloadCSVFile()" style="color:red;"><b>here</b></a>. Then you click on <b>Import CSV</b> link form menu <br>
            above and import csv file. After that all links from menu above will be available.
        </p>
    </div>
</template>
<script>
export default {
    methods: {
        downloadCSVFile () {
            axios({
                url: app_url+'/files/task_manager.csv',
                method: 'GET',
                responseType: 'blob',
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'task_manager.csv');
                document.body.appendChild(fileLink);
                fileLink.click();
            });
        }
    },
}
</script>
