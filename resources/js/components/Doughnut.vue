<script>
import {
    Doughnut
} from 'vue-chartjs'

export default {
    extends: Doughnut,
    data() {
        return {
            userRoles: '',
            labels: [],
            countRoles: [],
            backgroundColor: [],
            datacollection: {
                labels: '',
                datasets: [{
                    label: 'Data One',
                    backgroundColor: '',
                    pointBackgroundColor: 'white',
                    borderWidth: 1,
                    pointBorderColor: '#249EBF',
                    data: ''
                }]
            },
            options: {
                title: {
                    display: false,
                    text: 'User Roles',
                    fontSize: 20,
                    fontColor: '#212529'
                },
                tooltips: {
                    enabled: true,
                    mode: 'single',
                    title: 'text',
                    callbacks: {
                        label: function(tooltipItems, data) {
                            var countRole = data.datasets[0].data[tooltipItems.index];
                            var str = '';

                            if (countRole > 1) {
                                str = 's';
                            } else {
                                str = '';
                            }

                            var label = ' ' + data.datasets[0].data[tooltipItems.index] + ' ' + data.labels[tooltipItems.index] + str;
                            
                            return label;
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'left',
                    align: 'start',
                    padding: 300
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 120,
                        top: 0,
                        bottom: 0
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    async mounted() {
        // this.chartData is created in the mixin
        await axios.get('api/get-user-roles').then(response => this.userRoles = response.data);
        this.userRoles.forEach((element) => {
            this.labels.push(element.name);
            this.countRoles.push(element.count);
        });
        this.datacollection.labels = this.labels;
        this.datacollection.datasets[0].data = this.countRoles;
        var defaultColors = ['#192841', '#24385c', '#324f81', '#4065a5', '#5a7fbf', '#7e9bcd', '#a3b8db']
        this.datacollection.datasets[0].backgroundColor = defaultColors;
        this.renderChart(this.datacollection, this.options)
    }
}
</script>