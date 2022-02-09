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
                        <h2 class="text-white font-weight-bold my-2 mr-5">History Logs</h2>
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
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Track and Trace Employee</a>
                            <!--end::Item-->
                        </div>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Heading-->
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
                                     <a href="#" @click="refreshLogs"><i class="fas fa-sync text-primary icon-md"  title="Refresh" style="cursor:pointer;" ></i> Refresh</a>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex mb-9">
                            <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                                <div class="symbol symbol-50 symbol-lg-120">
                                    <img v-if="employee" :src="'http://10.96.4.126:8668/storage/id_image/employee_image/'+employee.id+'.png'" alt="image" @error="setAltImg">
                                </div>
                                <div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
                                    <span class="font-size-h3 symbol-label font-weight-boldest" v-if="employee">{{employee.first_name + ' ' + employee.last_name}}</span>
                                </div>
                            </div>
                            <div v-if="loading" class="mt-5">
                                <div class="spinner spinner-left spinner-primary spinner-lg">
                                    <span class="ml-15">Loading.. Please wait..</span>
                                </div>
                            </div>
                            <div v-else class="flex-grow-1">
                                <div class="d-flex justify-content-between flex-wrap mt-1">
                                    <div class="d-flex mr-3">
                                        <a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3" >{{employee.first_name + ' ' + employee.last_name}}</a>
                                        <a href="#">
                                            <i class="flaticon2-correct text-success font-size-h5"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between mt-1">
                                    <div class="d-flex flex-column flex-grow-1 pr-8">
                                        <div class="d-flex flex-wrap mb-4">
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                            <i class="flaticon2-new-email mr-2 font-size-lg"></i>{{ employee.user ? employee.user.email : ""}}</a>
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                            <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i>{{employee.position}}</a>
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                            <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i>{{ employee.departments ? employee.departments[0].name : ""}}</a>
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                            <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i>{{ employee.companies ? employee.companies[0].name : ""}}</a>
                                        </div>
                                    </div>
                                    <div v-if="employee_current_location_latest">
                                        <strong style="font-size:12px" class="text-success">{{ getCurrentLocation(employee_current_location_latest) }}</strong> <br>
                                        <small style="font-size:11px">{{ changeDateFormat(employee_current_location_latest.local_time) }}</small>
                                    </div>
                                    <div v-else>
                                        <span class="label font-weight-bold label-lg label-light-danger label-inline">Not Detected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            Show
                            <select v-model="itemsPerPageLog">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            Total : {{ filteredLogs.length }}
                        </div>

                        <div class="float-left mt-2">
                            <input type="date" id="from" v-model="from" @change="date_range">
                            <input type="date" id="to" v-model="to">
                            <button @click="filterLogs">Filter</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th>Location</th>
                                        <th>Date/Time</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(log, i) in filteredLogQueues" :key="i" >
                                        <td width="20px">
                                            <i class="fas fa-map-marker text-primary"></i>
                                        </td>
                                        <td>
                                            <strong style="font-size:12px" class="text-default">{{ getCurrentLocation(log) }}</strong> <br>
                                        </td>
                                        <td>
                                            <small style="font-size:12px">{{changeDateFormat(log.local_time)}}</small>
                                        </td>
                                        <td>
                                            <small style="font-size:12px">{{ calculateDuration(i) }}</small>
                                        </td>
                                    </tr>
                                    <tr v-if="loading_logs">
                                        <td colspan="3">
                                            <div class="spinner spinner-left spinner-primary spinner-lg">
                                                <span class="ml-15">Loading.. Please wait..</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row col-md-12" v-if="filteredLogQueues.length">
                            <div class="col-12">
                                <button :disabled="!showPreviousLinkLog()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageLog(currentPageLog - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPageLog + 1 }} of {{ totalPagesLog }}</span>
                                <button :disabled="!showNextLinkLog()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageLog(currentPageLog + 1)"> Next </button>
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
    export default {
        data() {
            return {
                keywords: '',
                employee : [],
                employee_current_location_logs : [],
                employee_current_location_latest : [],
                errors : [],
                currentPageLog: 0,
                itemsPerPageLog: 10,

                doors : [],

                loading:false,
                loading_logs:false,

                from : '',
                to: '',
            }
        },
        created () {
            this.getCurrentTransferAccessLogs();
            this.getRfidDoors();
            this.getHistoryLogs();
        },
        methods: {
            calculateDuration(start){
                let v = this;
                var end = Number(start) + 1;
                var from = '';
                var to = '';
                if(v.filteredLogs[start] && v.filteredLogs[end]){
                    from = v.filteredLogs[start].local_time;
                    to = v.filteredLogs[end].local_time;
                    return this.rendered(from,to);
                }
            },
            rendered(endTime, startTime){ 
                if(endTime && startTime){
                    var ms = moment(endTime,"YYYY/MM/DD HH:mm:ss a").diff(moment(startTime,"YYYY/MM/DD HH:mm:ss a"));
                    var d = moment.duration(ms);
                    var hours = Math.floor(d.asHours());
                    var minutes = moment.utc(ms).format("mm");
                    var seconds = moment.utc(ms).format("ss");
                    return hours + 'h '+ minutes+'m ' + seconds + 's'; 
                }else{
                    return 0;
                }                                  
            },
            date_range()
            {
                document.getElementById('to').min = this.from;
            },
            changeDateFormat(log_time){
                var new_log_time = moment(log_time).format('LL LTS');
                return new_log_time;
            },
            setAltImg(event) { 
                event.target.src = "/img/default.jpg"; 
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
            getHistoryLogs() {
                let v = this;
                v.employee = '';
                v.loading = true;
                axios.get('/view-history-logs-data?from='+this.from + '&to='+this.to)
                .then(response => { 
                    v.employee = response.data;
                    v.employee_current_location_logs = response.data.employee_current_location_logs;
                    v.employee_current_location_latest = response.data.employee_current_location_latest;
                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading = false;
                })
            },
            refreshLogs() {
                this.getCurrentTransferAccessLogs();
                let v = this;
                v.from = '';
                v.to = '';
                v.loading_logs = true;
                v.employee_current_location_logs = [];
                axios.get('/view-history-logs-data?employee_id='+v.employee.id)
                .then(response => { 
                    v.employee_current_location_latest = response.data.employee_current_location_latest;
                    v.employee_current_location_logs = response.data.employee_current_location_logs;
                    v.loading_logs = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading_logs = false;
                })
            },
            getCurrentTransferAccessLogs(){
                axios.get('/transfer-access-log');
            },
            filterLogs() {
                let v = this;
                v.loading_logs = true;
                v.employee_current_location_logs = [];
                axios.get('/view-history-logs-data?employee_id='+v.employee.id+'&from='+v.from+'&to='+v.to)
                .then(response => { 
                    v.employee_current_location_latest = response.data.employee_current_location_latest;
                    v.employee_current_location_logs = response.data.employee_current_location_logs;
                    v.loading_logs = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading_logs = false;
                })
            },
            setPageLog(pageNumber) {
                this.currentPageLog = pageNumber;
            },
            resetStartRowLog() {
                this.currentPageLog = 0;
            },
            showPreviousLinkLog() {
                return this.currentPageLog == 0 ? false : true;
            },
            showNextLinkLog() {
                return this.currentPageLog == (this.totalPagesLog - 1) ? false : true;
            },
        },
        computed: {
            filteredLogs(){
                let v = this;
                if(v.employee_current_location_logs){
                    return Object.values(v.employee_current_location_logs);
                }else{
                    return [];
                }
               
            },
            totalPagesLog() {
                return Math.ceil(Object.values(this.filteredLogs).length / Number(this.itemsPerPageLog))
            },
            filteredLogQueues() {
                var index = this.currentPageLog * Number(this.itemsPerPageLog);
                var queues_array = this.filteredLogs.slice(index, index + Number(this.itemsPerPageLog));

                if(this.currentPageLog >= this.totalPagesLog) {
                    this.currentPageLog = this.totalPagesLog - 1
                }

                if(this.currentPageLog == -1) {
                    this.currentPageLog = 0;
                }

                return queues_array;
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>