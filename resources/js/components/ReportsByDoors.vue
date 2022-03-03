<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Heading-->
                    <div class="d-flex flex-column">
                        <!--begin::Title-->
                        <h2 class="text-white font-weight-bold my-2 mr-5">By Doors</h2>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <!--begin::Item-->
                            <a href="#" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-shelter text-white icon-1x"></i>
                            </a>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Reports</a>
                            <!--end::Item-->
                        </div>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Heading-->
                </div>
            </div>
        </div>
    </div>

     <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="float-right">
                                 <a href="#" @click="refresh" class="text-default"><i class="fas fa-sync text-primary icon-md"  title="Refresh" style="cursor:pointer;"></i> Refresh  </a>
                                <a href="#" v-if="loading_download" @click="download" class="text-default">| <i class="fas fa-download text-default icon-md" title="Download" style="cursor:pointer;"></i> Download</a>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 mt-5 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer">
                                        <i class="fas fa-search text-dark-50"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search Door" v-model="keywords">
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        Show
                        <select v-model="itemsPerPageDoor">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        Total : {{ doors.length }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-checkable" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>DOOR</th>
                                    <th align="center">CURRENT SCANNED EMPLOYEES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(door, i) in filteredDoorQueues" :key="i" >
                                    <td>
                                        <small>{{door.door_name}}</small>
                                    </td>
                                    <td>
                                        <span class="text-success" style="cursor:pointer;" @click="showCurrentScannedEmployees(door)">{{ currentScannedEmployees(door) }}</span> 
                                    </td>
                                </tr>
                                <tr v-if="loading">
                                    <td colspan="6">
                                        <div class="col-md-3 pt-5 pb-5">
                                            <div class="spinner spinner-left spinner-primary spinner-lg">
                                                <span class="ml-15">Loading.. Please wait..</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row col-md-12" v-if="filteredDoorQueues.length">
                        <div class="col-12">
                            <button :disabled="!showPreviousLinkDoor()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageDoor(currentPageDoor - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPageDoor + 1 }} of {{ totalPagesDoor }}</span>
                            <button :disabled="!showNextLinkDoor()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageDoor(currentPageDoor + 1)"> Next </button>
                        </div>
                        <div class="col-12 text-right">
                            <span>Total : {{ filteredDoors.length }} </span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>

     <div class="modal fade" id="show-current-scanned-employees-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Current Scanned Employees</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search Employee.." v-model="keywords_employee">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="float-right">
                                Show
                                <select v-model="itemsPerPageEmployee">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                Total : {{ filteredEmployees.length }}
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
                                           <span style="font-size:11px"> {{employee.position}} | {{ employee.departments.length > 0 ? employee.departments[0].name : ""}} | {{ employee.locations.length > 0 ? employee.locations[0].name : ""}}</span> <br>
                                           <span style="font-size:11px"> {{ employee.door_id_number ? employee.door_id_number + ' |' : ""  }} {{employee.rfid_64}}</span>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <div v-if="employee.employee_current_location_latest">
                                                <strong style="font-size:12px;cursor:pointer;" :class="locationColor(employee.employee_current_location_latest)" @click="showMap(employee.employee_current_location_latest,employee)">{{ getCurrentLocation(employee.employee_current_location_latest) }}</strong> <br>
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
                                    <tr v-if="current_scanned_employees.length == 0">
                                        <td colspan="6">
                                            <small>No Records Found</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" v-if="filteredEmployeeQueues.length">
                        <div class="col-md-12">                        
                            <span class="float-right">
                                <button :disabled="!showPreviousLinkEmployee()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageEmployee(currentPageEmployee - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPageEmployee + 1 }} of {{ totalPagesEmployee }}</span>
                                <button :disabled="!showNextLinkEmployee()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageEmployee(currentPageEmployee + 1)"> Next </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button class="btn btn-primary btn-md" @click="updateRfidDoor" :disabled="saveDisable">Save</button> -->
                </div>
            </div>
        </div>
    </div>



</div>
</template>

<script>
    import Excel from 'exceljs';
    import FileSaver from 'file-saver';

    export default {
        data() {
            return {
                keywords : '',
                employees : [],
                doors : [],

                current_scanned_employees : '',

                currentPageDoor: 0,
                itemsPerPageDoor: 10,

                currentPageEmployee: 0,
                itemsPerPageEmployee: 10,

                loading : false,
                loading_download : false,

                keywords_employee : '',
            }
        },
        created () {
            this.getEmployees();
            this.getRfidDoors();
        },
        methods: {
            refresh(){
                this.getEmployees();
                this.getRfidDoors();
            },
            download(){
                let v = this;
                var workbook = new Excel.Workbook();
                var worksheet = workbook.addWorksheet('Employees',{pageSetup:{paperSize: 5, orientation:'landscape'}});
                worksheet.pageSetup.margins = {
                    left: 0.25, right: 0.25,
                    top: 0.75, bottom: 0.75,
                    header: 0.3, footer: 0.3
                };

                //Header-------------------------------------------------------------------?
                worksheet.columns = [{ width: 30 },{ width: 30},{ width: 30},{ width: 30},{ width: 30}];

                worksheet.getCell('A1').value = 'DOOR';
                worksheet.getCell('B1').value = 'CURRENT SCANNED EMPLOYEES';

                let worksheet_ctr = 2;
                v.filteredDoors.forEach(function(w){
                    worksheet.getCell('A'+worksheet_ctr).value = w.door_name;
                    worksheet.getCell('B'+worksheet_ctr).value = v.currentScannedEmployees(w);
                    worksheet_ctr++;
                });


                workbook.csv.writeBuffer().then(buffer => FileSaver.saveAs(new Blob([buffer]), `Reports_By_Doors.csv`)).catch(err => console.log('Error writing excel export', err));
            },
            changeDateFormat(log_time){
                var new_log_time = moment(log_time).format('LL LTS');
                return new_log_time;
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
            locationColor(current_location){
                let v = this;
                var location = Object.values(v.doors).filter(door => {
                    if(current_location.controller_id == door.controller_id && current_location.door_id == door.door_id){
                        return door;
                    }
                });
                if(location.length > 0){
                    if(location[0].rfid_controller){
                        if(location[0].rfid_controller.location == 'BGC'){
                            return 'text-success';
                        }
                        else if(location[0].rfid_controller.location == 'MANILA'){
                            return 'text-primary';
                        }
                        else if(location[0].rfid_controller.location == 'ILOILO'){
                            return 'text-warning';
                        }else {
                            return 'text-default';
                        }
                    }else{
                        return 'text-default';
                    }
                }
            },
            showCurrentScannedEmployees(door){
                let v  = this;
                var current_scanned_employees = Object.values(v.employees).filter(employee => {
                    if(employee.employee_current_location_latest){
                        if(employee.employee_current_location_latest.controller_id == door.controller_id && employee.employee_current_location_latest.door_id == door.door_id){
                            return employee;
                        }
                    }
                });
                v.current_scanned_employees = current_scanned_employees;

                if(current_scanned_employees.length > 0){
                    $('#show-current-scanned-employees-modal').modal('show');
                }
            },
            currentScannedEmployees(door){
                let v  = this;
                var current_scanned_employees = Object.values(v.employees).filter(employee => {
                    if(employee.employee_current_location_latest){
                        if(employee.employee_current_location_latest.controller_id == door.controller_id && employee.employee_current_location_latest.door_id == door.door_id){
                            return employee;
                        }
                    }
                });
                return current_scanned_employees.length > 0 ? current_scanned_employees.length : "";
            },
            getEmployees() {
                let v = this;
                v.loading_download = false;
                axios.get('/get-employees-data')
                .then(response => { 
                    v.employees = response.data;
                    v.loading_download = true;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            getRfidDoors() {
                let v = this;
                v.loading = true;
                v.doors = [];
                axios.get('/get-rfid-settings-doors-data')
                .then(response => { 
                    v.doors = response.data;
                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading = false;
                })
            },
            
            setPageDoor(pageNumber) {
                this.currentPageDoor = pageNumber;
            },
            resetStartRowDoor() {
                this.currentPageDoor = 0;
            },
            showPreviousLinkDoor() {
                return this.currentPageDoor == 0 ? false : true;
            },
            showNextLinkDoor() {
                return this.currentPageDoor == (this.totalPagesDoor - 1) ? false : true;
            },
            //Employee Pagination
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
            filteredDoors(){
                let v = this;
                if(v.doors){
                    return Object.values(v.doors).filter(door => {
                        return door.door_name.toLowerCase().includes(this.keywords.toLowerCase()) 
                    });
                }else{
                    return [];
                }
            },
            totalPagesDoor() {
                return Math.ceil(Object.values(this.filteredDoors).length / this.itemsPerPageDoor)
            },
            filteredDoorQueues() {
                var index = this.currentPageDoor * this.itemsPerPageDoor;
                var queues_array = this.filteredDoors.slice(index, index + this.itemsPerPageDoor);

                if(this.currentPageDoor >= this.totalPagesDoor) {
                    this.currentPageDoor = this.totalPagesDoor - 1
                }

                if(this.currentPageDoor == -1) {
                    this.currentPageDoor = 0;
                }

                return queues_array;
            },
            filteredEmployees(){
                let v = this;
                if(v.current_scanned_employees){
                    return Object.values(v.current_scanned_employees).filter(employee => {
                        var full_name = employee.first_name + ' ' + employee.last_name;
                        return full_name.toLowerCase().includes(this.keywords_employee.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords_employee.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords_employee.toLowerCase())     
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
        },
    }
</script>

<style lang="scss" scoped>

</style>