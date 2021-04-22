<template>
    <div class="card">
        <div class="card-header">
            <h1 class="custom-h1-title">Reports</h1>
        </div>
        <div class="card-body" v-if="this.$root.checkImportCSV > 0">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <bar-chart></bar-chart>
                </div>
                <div class="col-lg-6 col-sm-12">
                   <p class="text-center p-doughnut p-doughnut-sm"><b>User Roles</b></p>
                    <doughnut-chart></doughnut-chart>
                </div>
            </div>
        </div>
        <div v-else><default-message></default-message></div> 
    </div>
</template>
