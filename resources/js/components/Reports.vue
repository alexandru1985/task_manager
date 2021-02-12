<template>
    <div>
        <h1 class="custom-h1-title">Reports</h1>
        <div v-if="this.$root.checkImportCSV > 0">
            <div class="row">
                <div class="col-6">
                    <bar-chart></bar-chart>
                </div>
                <div class="col-6">
                   <p class="text-center" style="font-size:20px; fontColor:#212529;padding-left:60px; padding-top:8px;"><b>User Roles</b></p>
                    <doughnut-chart></doughnut-chart>
                </div>
            </div>
        </div>
        <div v-else><default-message></default-message></div> 
    </div>
</template>
