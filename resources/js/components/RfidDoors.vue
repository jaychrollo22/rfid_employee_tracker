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
                        <h2 class="text-white font-weight-bold my-2 mr-5">RFID Doors</h2>
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
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Settings</a>
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
                            <div class="col-sm-12">
                                <div class="float-right">
                                    <button class="btn btn-sm btn-primary" @click="addRfidDoor()" :disabled="saveDisable">New Door</button>
                                </div>
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
                                        <th>ID</th>
                                        <th>CONTROLLER</th>
                                        <th>DOOR</th>
                                        
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(door, i) in filteredDoorQueues" :key="i" >
                                       <td><small>{{door.door_id}}</small></td>
                                       <td>
                                           <small>{{door.rfid_controller ? door.rfid_controller.controller_name : ""}}</small>
                                       </td>
                                       <td><small>{{door.door_name}}</small></td>
                                       <td align="center">
                                           <i class="fas fa-pen text-primary" style="cursor:pointer;" @click="editDoor(door)"></i>
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
    </div>

    <!-- ADD RFID DOOR -->
    <div class="modal fade" id="add-rfid-door-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Add RFID Door</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Door ID" v-model="door.door_id">
                            </div>
                            <span class="text-danger" v-if="errors.door_id">{{ errors.door_id[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Door Name" v-model="door.door_name" @input="door.door_name=$event.target.value.toUpperCase()">
                            </div>
                            <span class="text-danger" v-if="errors.door_name">{{ errors.door_name[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-cogs"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="door.controller_id">
                                    <option value="">Choose Controller</option>
                                    <option v-for="(controller, i) in controllers" :key="i" :value="controller.controller_id">{{controller.controller_name}}</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.door_id">{{ errors.door_id[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveRfidDoor" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT RFID DOOR -->
    <div class="modal fade" id="edit-rfid-door-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Edit RFID Door</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Door ID" v-model="edit_door.door_id">
                            </div>
                            <span class="text-danger" v-if="errors.door_id">{{ errors.door_id[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Door Name" v-model="edit_door.door_name" @input="edit_door.door_name=$event.target.value.toUpperCase()">
                            </div>
                            <span class="text-danger" v-if="errors.door_name">{{ errors.door_name[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-cogs"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="edit_door.controller_id">
                                    <option value="">Choose Controller</option>
                                    <option v-for="(controller, i) in controllers" :key="i" :value="controller.controller_id">{{controller.controller_name}}</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.door_id">{{ errors.door_id[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="updateRfidDoor" :disabled="saveDisable">Save</button>
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
                door : {
                    'door_id' : '',
                    'door_name' : '',
                    'controller_id' : ''
                },
                edit_door : {
                    'id' : '',
                    'door_id' : '',
                    'door_name' : '',
                    'controller_id' : ''
                },
                doors : [],
                errors : [],
                currentPageDoor: 0,
                itemsPerPageDoor: 10, 

                saveDisable : false, 
                loading : false,

                controllers : []
            }
        },
        created () {
            this.getRfidDoors();
            this.getRfidControllers();
        },
        methods: {
            updateRfidDoor(){
                let v = this;
                v.saveDisable = true;
                Swal.fire({
                    title: 'Are you sure you want to update this door?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.edit_door.id ? v.edit_door.id : "");
                        formData.append('door_id', v.edit_door.door_id ? v.edit_door.door_id : "");
                        formData.append('door_name', v.edit_door.door_name ? v.edit_door.door_name : "");
                        formData.append('controller_id', v.edit_door.controller_id ? v.edit_door.controller_id : "");
                        axios.post(`/update-rfid-settings-door`, formData)
                        .then(response =>{
                            if(response.data.status == "saved"){
                                var index = v.doors.findIndex(item => item.id == v.edit_door.id);
                                v.doors.splice(index,1,response.data.door);
                                Swal.fire('RFID Door has been updated.', '', 'success');        
                                $('#edit-rfid-door-modal').modal('hide');
                                v.clearFieldsRfidDoor();
                                v.saveDisable = false;
                            }else{
                                Swal.fire('Error: Cannot saved. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            v.errors = error.response.data.errors;
                            v.saveDisable = false;
                        })
                    }else{
                        v.saveDisable = false;
                    }   
                })
            },
            editDoor(door){
                let v = this;
                v.edit_door.id = door.id;
                v.edit_door.door_id = String(door.door_id);
                v.edit_door.door_name = door.door_name;
                v.edit_door.controller_id = door.controller_id;
                $('#edit-rfid-door-modal').modal('show');
            },
            addRfidDoor(){
                let v = this;
                v.clearFieldsRfidDoor();
                $('#add-rfid-door-modal').modal('show');
            },
            clearFieldsRfidDoor(){
                let v = this;
                //Clear Add
                v.door.door_id = '';
                v.door.door_name = '';
                v.door.controller_id = '';
                //Clear Edit
                v.edit_door.door_id = '';
                v.edit_door.door_name = '';
                v.edit_door.controller_id = '';
            },
            saveRfidDoor(){
                let v = this;
                v.saveDisable = true;
                Swal.fire({
                    title: 'Are you sure you want to save this door?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('door_id', v.door.door_id ? v.door.door_id : "");
                        formData.append('door_name', v.door.door_name ? v.door.door_name : "");
                        formData.append('controller_id', v.door.controller_id ? v.door.controller_id : "");
                        axios.post(`/save-rfid-settings-door`, formData)
                        .then(response =>{
                            if(response.data.status == "saved"){
                                v.doors.unshift(response.data.door);   
                                Swal.fire('New RFID Door has been saved.', '', 'success');        
                                $('#add-rfid-door-modal').modal('hide');
                                v.clearFieldsRfidDoor();
                                v.saveDisable = false;
                            }else{
                                Swal.fire('Error: Cannot saved. Please try again.', '', 'error');
                                v.saveDisable = false;
                            }
                        })
                        .catch(error => {
                            v.errors = error.response.data.errors;
                            v.saveDisable = false;
                        })
                    }else{
                        v.saveDisable = false;
                    }   
                })
            },

            getRfidControllers() {
                let v = this;
                v.controllers = [];
                axios.get('/get-rfid-settings-controllers-data')
                .then(response => { 
                    v.controllers = response.data;
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
        },
    }
</script>

<style lang="scss" scoped>

</style>