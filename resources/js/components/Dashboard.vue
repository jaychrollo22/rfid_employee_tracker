<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">Dashboard</h2>
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Employee Tracker Per Locations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4">
                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <div class="text-enter">
                                    <center>
                                        <vue-ellipse-progress :progress="progressTotalInOffice" :legendValue="totalInOffice.length" fontSize="2.5rem" color="#003494">
                                            <span slot="legend-value"> </span>
                                            <p slot="legend-caption" style="cursor:pointer;"><strong>Total In Office</strong></p>
                                        </vue-ellipse-progress>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <div class="text-enter">
                                    <div class="row">
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="progressBgc" :legendValue="actualInBGC.length" color="#00713A" :size="150">
                                                    <span slot="legend-value"> / {{ totalInBGC.length }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>BGC</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                            
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="60" :legendValue="60" color="#E2231A" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>MANILA</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="80" :legendValue="60" color="#FFD840" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>ILOILO</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="80" :legendValue="60" color="#FF8300" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>DAVAO</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




    </div>
</div>
</template>

<script>
    import VueEllipseProgress from 'vue-ellipse-progress';
    export default {
        data() {
            return {
                employees : [],
                errors: [],
                loading: false,

                totalInOffice : [],

                actualInBGC : [],
                totalInBGC : []
            }
        },
        created () {
            this.getEmployees();
        },
        methods: {
            getEmployees() {
                let v = this;
                v.loading = true;
                v.employees = [];
                v.totalInOffice = [];
                v.actualInBGC = [];
                v.totalInBGC = [];
                axios.get('/get-employees-data')
                .then(response => { 
                    v.employees = response.data;
                    response.data.forEach(e => {

                        //Total In BGC
                        if(e.locations.length > 0){
                            if(e.locations[0].name == "BGC TAGUIG"){
                                v.totalInBGC.push(e);
                            }
                        }

                        //In Office
                        if(e.employee_current_location_latest){
                            v.totalInOffice.push(e);

                            //Actual in BGC
                            if(e.employee_current_location_latest.rfid_controller){
                                if(e.employee_current_location_latest.rfid_controller.location == "BGC"){
                                    v.actualInBGC.push(e);
                                }
                            }
                        }
                    });

                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading = false;
                })
            },
        },
        computed: {
            progressTotalInOffice(){
                let  v = this;
                if(v.totalInOffice.length > 0 && v.employees.length > 0){
                    var progress = (v.totalInOffice.length / v.employees.length) * 100;
                    return progress.toFixed(0);
                }else{
                    return 0;
                }
                
            },
            progressBgc(){
                let  v = this;
                if(v.actualInBGC.length > 0 && v.totalInBGC.length > 0){
                    var progress = (v.actualInBGC.length / v.totalInBGC.length) * 100;
                    return progress.toFixed(0);
                }else{
                    return 0;
                }
                
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>