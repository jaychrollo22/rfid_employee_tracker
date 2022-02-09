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
                                            <p slot="legend-caption" @click="viewSelectedEmployees(totalInOffice)" style="cursor:pointer;"><strong>Total In Office</strong></p>
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
                                                    <p slot="legend-caption" @click="viewSelectedEmployees(actualInBGC)" style="cursor:pointer;"><strong>BGC</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                            
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="progressManila" :legendValue="actualInManila.length" color="#E2231A" :size="150">
                                                    <span slot="legend-value"> / {{ totalInManila.length }}</span>
                                                    <p slot="legend-caption" @click="viewSelectedEmployees(actualInManila)" style="cursor:pointer;"><strong>MANILA</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="progressIloilo" :legendValue="actualInIloilo.length" color="#FFD840" :size="150">
                                                    <span slot="legend-value"> / {{ totalInIloilo.length }}</span>
                                                    <p slot="legend-caption" @click="viewSelectedEmployees(actualInIloilo)" style="cursor:pointer;"><strong>ILOILO</strong></p>
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

    <!-- Show Employees -->
    <div class="modal fade" id="view-data-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fixed" style="width:100%!important;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Employees</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" class="form-control" placeholder="Input here..." v-model="keywords">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center"></th>
                                        <th>Employee</th>
                                        <th>Current Location</th>
                                        <th class="text-center">Logs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(employee, i) in filteredEmployeeQueues" :key="i" >
                                        <td width="20px" align="center" style="vertical-align: middle;">
                                            <div v-if="employee.user_favorite.length > 0">
                                                <i v-if="employee.user_favorite[0].status == '1'" class="fas fa-star text-warning" style="cursor:pointer;" @click="saveFavorite(employee)"></i>
                                                <i v-else class="far fa-star" style="cursor:pointer;" @click="saveFavorite(employee)"></i>
                                            </div>
                                            <div v-else>
                                                <i class="far fa-star" style="cursor:pointer;" @click="saveFavorite(employee)"></i>
                                            </div>
                                        </td>
                                        <td width="20px" align="center" style="vertical-align: middle;">
                                            <i class="fas fa-id-badge text-success" v-if="employee.rfid_64" title="Registered"></i>
                                            <i class="fas fa-id-badge text-danger" v-else title="Not Registered"></i>
                                        </td>
                                        <td style="vertical-align: middle;">
                                           <strong style="font-size:12px">{{employee.last_name + ', ' + employee.first_name}}</strong><br>
                                           <span style="font-size:11px"> {{employee.position}} | {{ employee.departments.length > 0 ? employee.departments[0].name : ""}} | {{ employee.companies.length > 0 ? employee.companies[0].name : ""}}</span> <br>
                                           <span style="font-size:11px"> {{ employee.door_id_number ? employee.door_id_number + ' |' : ""  }} {{employee.rfid_64}}</span>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <div v-if="employee.employee_current_location_latest">
                                                <strong style="font-size:12px" class="text-success">{{ getCurrentLocation(employee.employee_current_location_latest) }}</strong> <br>
                                                <strong style="font-size:11px">{{ changeDateFormat(employee.employee_current_location_latest.local_time)}}</strong>
                                            </div>
                                            <div v-else>
                                                <span class="label font-weight-bold label-lg label-light-danger label-inline">Not Detected</span>
                                            </div>
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            <a :href="'/view-history-logs?id='+employee.id+'&v=' + Math.random()" target="_blank"><i class="fas fa-street-view text-warning" style="cursor:pointer;" title="View Logs"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="selected_employees.length == 0">
                                        <td colspan="6" align="center">
                                            <i class="fa-solid fa-glass-empty"></i> <br>
                                            <small>Empty</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row col-md-12" v-if="filteredEmployeeQueues.length">
                        <div class="col-12">
                            <button :disabled="!showPreviousLinkEmployee()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageEmployee(currentPageEmployee - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPageEmployee + 1 }} of {{ totalPagesEmployee }}</span>
                            <button :disabled="!showNextLinkEmployee()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageEmployee(currentPageEmployee + 1)"> Next </button>
                        </div>
                        <div class="col-12 text-right">
                            <span>Total : {{ filteredEmployees.length }} </span><br>
                        </div>
                    </div>
                </div>
                 <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default waves-effect " data-dismiss="modal" @click="closeViewContent">Close</button> -->
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
                totalInBGC : [],

                actualInManila : [],
                totalInManila : [],

                actualInIloilo : [],
                totalInIloilo : [],

                keywords: '',
                currentPageEmployee: 0,
                itemsPerPageEmployee: 10,
                selected_employees : [],
            }
        },
        created () {
            this.getCurrentTransferAccessLogs();
            this.getRfidDoors();
            this.getEmployees();
        },
        methods: {
            saveFavorite(employee){
                let v = this;
                if(employee){
                    var index = v.selected_employees.findIndex(item => item.id == employee.id);
                    let formData = new FormData();
                    formData.append('employee_id', employee.id ? employee.id : "");
                    axios.post(`/save-user-favorite`, formData)
                    .then(response =>{
                        if(response.data.status == "saved"){
                            v.selected_employees.splice(index,1,response.data.employee);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
                }
            },
            getCurrentTransferAccessLogs(){
                axios.get('/transfer-access-log');
            },
            getCurrentLocation(current_location){
                let v = this;
                var location = Object.values(v.doors).filter(door => {
                    if(current_location.controller_id == door.controller_id && current_location.door_id == door.door_id){
                        return door;
                    }
                });
                if(location.length > 0){
                    return location[0].door_name;
                }
            },
            getRfidDoors() {
                let v = this;
                v.doors = [];
                axios.get('/get-rfid-settings-doors-data')
                .then(response => { 
                    v.doors = response.data;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            changeDateFormat(log_time){
                var new_log_time = moment(log_time).format('LL LTS');
                return new_log_time;
            },
            viewSelectedEmployees(selected_employees){
                this.selected_employees = selected_employees;
                $('#view-data-modal').modal('show');
            },
            getEmployees() {
                let v = this;
                v.loading = true;
                v.employees = [];
                v.totalInOffice = [];
                v.actualInBGC = [];
                v.totalInBGC = [];

                v.actualInManila = [];
                v.totalInManila = [];
                v.actualInIloilo = [];
                v.totalInIloilo = [];
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

                        //Total In Manila
                        if(e.locations.length > 0){
                            if(e.locations[0].name == "MANILA"){
                                v.totalInManila.push(e);
                            }
                        }
                        //Total In Iloilo
                        if(e.locations.length > 0){
                            if(e.locations[0].name == "ILOILO"){
                                v.totalInIloilo.push(e);
                            }
                        }

                        //In Office
                        if(e.employee_current_location_latest){
                            v.totalInOffice.push(e);
                            if(e.employee_current_location_latest.rfid_controller){
                                //Actual in BGC
                                if(e.employee_current_location_latest.rfid_controller.location == "BGC"){
                                    v.actualInBGC.push(e);
                                }
                                //Actual in Manila
                                if(e.employee_current_location_latest.rfid_controller.location == "MANILA"){
                                    v.actualInManila.push(e);
                                }
                                //Actual in Iloilo
                                if(e.employee_current_location_latest.rfid_controller.location == "ILOILO"){
                                    v.actualInIloilo.push(e);
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
            setPageEmployee(pageNumber) {
                this.currentPageEmployee = pageNumber;
            },
            resetStartRowEmployee() {
                this.currentPageEmployee = 0;
            },
            showPreviousLinkEmployee() {
                return this.currentPageEmployee == 0 ? false : true;
            },
            showNextLinkEmployee() {
                return this.currentPageEmployee == (this.totalPagesEmployee - 1) ? false : true;
            },
        },
        computed: {
            filteredEmployees(){
                let v = this;
                if(v.selected_employees){
                    return Object.values(v.selected_employees).filter(employee => {
                        var full_name = employee.first_name + ' ' + employee.last_name;
                        return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase())     
                    });
                }else{
                    return [];
                }
               
            },
            totalPagesEmployee() {
                return Math.ceil(Object.values(this.filteredEmployees).length / Number(this.itemsPerPageEmployee))
            },
            filteredEmployeeQueues() {
                var index = this.currentPageEmployee * Number(this.itemsPerPageEmployee);
                var queues_array = this.filteredEmployees.slice(index, index + Number(this.itemsPerPageEmployee));

                if(this.currentPageEmployee >= this.totalPagesEmployee) {
                    this.currentPageEmployee = this.totalPagesEmployee - 1
                }

                if(this.currentPageEmployee == -1) {
                    this.currentPageEmployee = 0;
                }

                return queues_array;
            },
            progressTotalInOffice(){
                let  v = this;
                if(v.totalInOffice.length > 0 && v.employees.length > 0){
                    var progress = (v.totalInOffice.length / v.employees.length) * 100;
                    return Number(progress.toFixed(0));
                }else{
                    return 0;
                }
                
            },
            progressBgc(){
                let  v = this;
                if(v.actualInBGC.length > 0 && v.totalInBGC.length > 0){
                    var progress = (v.actualInBGC.length / v.totalInBGC.length) * 100;
                    return Number(progress.toFixed(0));
                }else{
                    return 0;
                }
            },
            progressManila(){
                let  v = this;
                if(v.actualInManila.length > 0 && v.totalInManila.length > 0){
                    var progress = (v.actualInManila.length / v.totalInManila.length) * 100;
                    return Number(progress.toFixed(0));
                }else{
                    return 0;
                }
            },
            progressIloilo(){
                let  v = this;
                if(v.actualInIloilo.length > 0 && v.totalInIloilo.length > 0){
                    var progress = (v.actualInIloilo.length / v.totalInIloilo.length) * 100;
                    return Number(progress.toFixed(0));
                }else{
                    return 0;
                }
            },
        },
    }
</script>

<style lang="scss" scoped>
    .modal-fixed {
        max-width: 1200px!important;
    }
</style>