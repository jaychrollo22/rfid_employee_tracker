<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">Employees</h2>
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <a href="#" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-shelter text-white icon-1x"></i>
                            </a>
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Track and Trace Employee</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="float-right">
                                    <i class="fas fa-sync text-primary icon-md"  title="Refresh" style="cursor:pointer;" @click="refresh"></i> Refresh |
                                    <i class="fas fa-id-badge text-success" title="Registered" style="cursor:pointer;" @click="showFilterEmployees('1')"></i> {{totalRegistered.length}} | 
                                    <i class="fas fa-id-badge text-danger icon-md" title="Not Registered" style="cursor:pointer;" @click="showFilterEmployees('0')"></i> {{totalNotRegistered.length}} | 
                                    <i class="fas fa-download text-default icon-md" title="Download" style="cursor:pointer;" @click="download"></i> Download
                                   
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mt-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer">
                                            <i class="fas fa-search text-dark-50"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search Employee" v-model="keywords">
                                </div>
                            </div>
                        </div>
                        <div class="float-right mt-3">
                            Show
                            <select v-model="itemsPerPageEmployee">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            Total : {{ filteredEmployees.length }}
                        </div>
                        <div class="float-left mt-5">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" v-model="show_favorites" @change="saveSessionShowFavorites" />
                                <label class="form-check-label" for="flexCheckDefault">
                                    &nbsp;Show Favorites
                                </label>
                            </div>
                        </div>
                        
                        <!-- <hr> -->
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
                                                <small style="font-size:11px">{{ changeDateFormat(employee.employee_current_location_latest.local_time)}}</small>
                                            </div>
                                            <div v-else>
                                                <span class="label font-weight-bold label-lg label-light-danger label-inline">Not Detected</span>
                                            </div>
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            <a :href="'/view-history-logs?id='+employee.id+'&v=' + Math.random()" target="_blank"><i class="fas fa-street-view text-warning" style="cursor:pointer;" title="View Logs"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="6">
                                            <div class="col-md-12 pt-5 pb-5">
                                                <div class="spinner spinner-left spinner-primary spinner-lg">
                                                    <span class="ml-15">Loading.. Please wait..</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                keywords: '',
                employee : [],
                employees : [],
                errors : [],
                currentPageEmployee: 0,
                itemsPerPageEmployee: 10,
                
                rfid_64_status : '',
                loading : false,

                doors : [],

                show_favorites : false,
            }
        },
        created () {
            this.getRfidDoors();
            this.getEmployees();
            this.getSessionShowFavorites();
        },
        methods: {
            changeDateFormat(log_time){
                var new_log_time = moment(log_time).format('LL LTS');
                return new_log_time;
            },
            saveSessionShowFavorites(){
                let v = this;
                let formData = new FormData();
                formData.append('show_favorites', v.show_favorites);
                axios.post(`/save-session-show-favorites`, formData)
                .then(response =>{
                    if(response.data){
                        v.show_favorites = response.data;
                    }
                })
            },
            getSessionShowFavorites(){
                let v = this;
                axios.get(`/get-session-show-favorites`)
                .then(response =>{
                    if(response.data){
                        v.show_favorites = response.data;
                    }
                })
            },
            saveFavorite(employee){
                let v = this;
                if(employee){
                    var index = v.employees.findIndex(item => item.id == employee.id);
                    let formData = new FormData();
                    formData.append('employee_id', employee.id ? employee.id : "");
                    axios.post(`/save-user-favorite`, formData)
                    .then(response =>{
                        if(response.data.status == "saved"){
                            v.employees.splice(index,1,response.data.employee);
                        }
                    })
                    .catch(error => {
                        v.errors = error.response.data.errors;
                    })
                }
            },
            refresh(){
                this.getRfidDoors();
                this.getEmployees();
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
            showFilterEmployees(status){
               this.rfid_64_status = status; 
            },
            getEmployees() {
                let v = this;
                v.loading = true;
                v.rfid_64_status = '';
                v.employees = [];
                axios.get('/get-employees-data')
                .then(response => { 
                    v.employees = response.data;
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

                worksheet.getCell('A1').value = 'DisplayName';
                worksheet.getCell('B1').value = 'FirstName';
                worksheet.getCell('C1').value = 'MiddleName';
                worksheet.getCell('D1').value = 'LastName';
                worksheet.getCell('E1').value = 'EmployeeID';
                worksheet.getCell('F1').value = 'Department';
                worksheet.getCell('G1').value = 'JobTitle';
                worksheet.getCell('H1').value = 'CardNo';
                worksheet.getCell('I1').value = 'CodeType';
                worksheet.getCell('J1').value = 'CardType';
                worksheet.getCell('K1').value = 'CardStatus';

                let worksheet_ctr = 2;
                v.filteredEmployees.forEach(function(w){
                    var display_name = w.first_name + ' ' + w.last_name;
                    worksheet.getCell('A'+worksheet_ctr).value = display_name;
                    worksheet.getCell('B'+worksheet_ctr).value = w.first_name;
                    worksheet.getCell('C'+worksheet_ctr).value = w.middle_name;
                    worksheet.getCell('D'+worksheet_ctr).value = w.last_name;
                    worksheet.getCell('E'+worksheet_ctr).value = w.id;
                    worksheet.getCell('F'+worksheet_ctr).value = w.departments ? w.departments[0].name : "";
                    worksheet.getCell('G'+worksheet_ctr).value = w.position;

                    if(w.rfid_64){
                        let rfid_64 = '0' + w.rfid_64;
                        let formated_rfid_64=rfid_64.match(/.{1,4}/g);
                        worksheet.getCell('H'+worksheet_ctr).value = formated_rfid_64.join(' ');
                    }else{
                        worksheet.getCell('H'+worksheet_ctr).value = '';
                    }

                    worksheet.getCell('I'+worksheet_ctr).value = '64';
                    worksheet.getCell('J'+worksheet_ctr).value = '1';
                    worksheet.getCell('K'+worksheet_ctr).value = '0';

                    worksheet_ctr++;
                })

                //Download
                workbook.csv.writeBuffer().then(buffer => FileSaver.saveAs(new Blob([buffer]), `EMPLOYEES.csv`)).catch(err => console.log('Error writing excel export', err));

            }
        },
        computed: {
            filteredEmployees(){
                let v = this;
                if(v.employees){
                    return Object.values(v.employees).filter(employee => {
                        var full_name = employee.first_name + ' ' + employee.last_name;
                        if(v.show_favorites){
                            if(employee.user_favorite.length > 0){
                                if(employee.user_favorite[0].status == '1'){
                                    if(v.rfid_64_status){
                                        if(v.rfid_64_status == '1'){
                                            if(employee.rfid_64){
                                                return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.rfid_64.toLowerCase().includes(this.keywords.toLowerCase())
                                            }
                                        }
                                        else if(v.rfid_64_status == '0'){
                                            if(employee.rfid_64 == "" || employee.rfid_64 == null){
                                                return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase())
                                            }
                                        }
                                    }else{
                                        return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) 
                                    }
                                }
                            }
                        }else{
                            if(v.rfid_64_status){
                                if(v.rfid_64_status == '1'){
                                    if(employee.rfid_64){
                                        return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.rfid_64.toLowerCase().includes(this.keywords.toLowerCase())
                                    }
                                }
                                else if(v.rfid_64_status == '0'){
                                    if(employee.rfid_64 == "" || employee.rfid_64 == null){
                                        return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase())
                                    }
                                }
                            }else{
                                return full_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) 
                            }
                        }
                        
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
            totalRegistered(){
                let v = this;
                if(v.employees){
                    return Object.values(v.employees).filter(employee => {
                        if(employee.rfid_64){
                            return employee;
                        }
                    });
                }else{
                    return [];
                }
            },
            totalNotRegistered(){
                let v = this;
                if(v.employees){
                    return Object.values(v.employees).filter(employee => {
                        if(employee.rfid_64 == "" || employee.rfid_64 == null){
                            return employee;
                        }
                    });
                }else{
                    return [];
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>