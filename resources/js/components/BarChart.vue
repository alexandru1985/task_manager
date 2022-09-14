<script>
import {
    Bar
} from 'vue-chartjs'

export default {
    extends: Bar,
    data: function() {
        return {
            userTasks: '',
            labels: [],
            tasks: [],
            datacollection: {
                labels: '',
                datasets: [{
                    label: 'Data One',
                    backgroundColor: '#192841',
                    pointBackgroundColor: 'white',
                    borderWidth: 1,
                    pointBorderColor: '#249EBF',
                    data: [60, 40, 20, 50, 90, 10, 20, 40, 50, 70, 90, 100]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'User Tasks',
                    fontSize: 20,
                    fontColor: '#212529'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: true,
                    mode: 'single',
                    callbacks: {
                        label: function(tooltipItems, data) {
                            var label = '';
                            if (tooltipItems.yLabel < 2) {
                                label = ' ' + tooltipItems.yLabel + ' task';
                            } else {
                                label = ' ' + tooltipItems.yLabel + ' tasks';
                            }
                            
                            return label;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                height: 200
            }
        }
    },
    methods: {},
    created() {

    },
    async mounted() {
        
        // this.chartData is created in the mixin
        await axios.get('api/get-user-tasks').then(response => this.userTasks = response.data);
        this.userTasks.forEach((element) => {
            this.labels.push(element.name);
            this.tasks.push(element.tasks);
        });
        this.datacollection.labels = this.labels;
        this.datacollection.datasets[0].data = this.tasks;
        this.renderChart(this.datacollection, this.options)
    }
}
</script>
