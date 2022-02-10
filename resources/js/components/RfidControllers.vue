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
                        <h2 class="text-white font-weight-bold my-2 mr-5">RFID Controllers</h2>
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
                                    <button class="btn btn-sm btn-primary" @click="addRfidController()" :disabled="saveDisable">New Controller</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mt-5 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer">
                                            <i class="fas fa-search text-dark-50"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search Controller" v-model="keywords">
                                </div>
                            </div>
                        </div>

                        <div class="float-right">
                            Show
                            <select v-model="itemsPerPageController">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            Total : {{ controllers.length }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>LOCATION</th>
                                        <th>CONTROLLER</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(controller, i) in filteredControllerQueues" :key="i" >
                                       <td><small>{{controller.controller_id}}</small></td>
                                       <td><small>{{controller.location}}</small></td>
                                       <td><small>{{controller.controller_name}}</small></td>
                                       <td align="center">
                                           <i class="fas fa-pen text-primary" style="cursor:pointer;" @click="editController(controller)"></i>
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
                        <div class="row col-md-12" v-if="filteredControllerQueues.length">
                            <div class="col-12">
                                <button :disabled="!showPreviousLinkController()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageController(currentPageController - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPageController + 1 }} of {{ totalPagesController }}</span>
                                <button :disabled="!showNextLinkController()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageController(currentPageController + 1)"> Next </button>
                            </div>
                            <div class="col-12 text-right">
                                <span>Total : {{ filteredControllers.length }} </span><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD RFID CONTROLLER -->
    <div class="modal fade" id="add-rfid-controller-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Add RFID Controller</h2>
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
                                <input type="text" class="form-control" placeholder="Controller ID (AS MANAGER)" v-model="controller.controller_id">
                            </div>
                            <span class="text-danger" v-if="errors.controller_id">{{ errors.controller_id[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Controller Name" v-model="controller.controller_name" @input="controller.controller_name=$event.target.value.toUpperCase()">
                            </div>
                            <span class="text-danger" v-if="errors.controller_name">{{ errors.controller_name[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-map-marker"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="controller.location">
                                    <option value="">CHOOSE LOCATION</option>
                                    <option value="BATAAN">BATAAN</option>
                                    <option value="BGC">BGC</option>
                                    <option value="ILOILO">ILOILO</option>
                                    <option value="MANILA">MANILA</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.controller_name">{{ errors.controller_name[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveRfidController" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT RFID CONTROLLER -->
    <div class="modal fade" id="edit-rfid-controller-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Edit RFID Controller</h2>
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
                                <input type="text" class="form-control" placeholder="Controller ID (AS MANAGER)" v-model="edit_controller.controller_id">
                            </div>
                            <span class="text-danger" v-if="errors.controller_id">{{ errors.controller_id[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Controller Name" v-model="edit_controller.controller_name" @input="edit_controller.controller_name=$event.target.value.toUpperCase()">
                            </div>
                            <span class="text-danger" v-if="errors.controller_name">{{ errors.controller_name[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-map-marker"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="edit_controller.location">
                                    <option value="">CHOOSE LOCATION</option>
                                    <option value="BATAAN">BATAAN</option>
                                    <option value="BGC">BGC</option>
                                    <option value="ILOILO">ILOILO</option>
                                    <option value="MANILA">MANILA</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.controller_name">{{ errors.controller_name[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="updateRfidController" :disabled="saveDisable">Save</button>
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
                controller : {
                    'controller_id' : '',
                    'controller_name' : '',
                    'location' : ''
                },
                edit_controller : {
                    'id' : '',
                    'controller_id' : '',
                    'controller_name' : '',
                    'location' : ''
                },
                controllers : [],
                errors : [],
                currentPageController: 0,
                itemsPerPageController: 10,

                saveDisable : false, 
                loading : false,
            }
        },
        created () {
            this.getRfidControllers();
        },
        methods: {
            updateRfidController(){
                let v = this;
                v.saveDisable = true;
                Swal.fire({
                    title: 'Are you sure you want to update this controller?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.edit_controller.id ? v.edit_controller.id : "");
                        formData.append('controller_id', v.edit_controller.controller_id ? v.edit_controller.controller_id : "");
                        formData.append('controller_name', v.edit_controller.controller_name ? v.edit_controller.controller_name : "");
                        formData.append('location', v.edit_controller.location ? v.edit_controller.location : "");
                        axios.post(`/update-rfid-settings-controller`, formData)
                        .then(response =>{
                            if(response.data.status == "saved"){
                                var index = v.controllers.findIndex(item => item.id == v.edit_controller.id);
                                v.controllers.splice(index,1,response.data.controller);
                                Swal.fire('RFID Controller has been updated.', '', 'success');        
                                $('#edit-rfid-controller-modal').modal('hide');
                                v.clearFieldsRfidController();
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
            editController(controller){
                let v = this;
                v.edit_controller.id = controller.id;
                v.edit_controller.controller_id = String(controller.controller_id);
                v.edit_controller.controller_name = controller.controller_name;
                v.edit_controller.location = controller.location;
                $('#edit-rfid-controller-modal').modal('show');
            },
            addRfidController(){
                let v = this;
                v.clearFieldsRfidController();
                $('#add-rfid-controller-modal').modal('show');
            },
            clearFieldsRfidController(){
                let v = this;
                //Clear Add
                v.controller.controller_id = '';
                v.controller.controller_name = '';
                v.controller.location = '';
                //Clear Edit
                v.edit_controller.controller_id = '';
                v.edit_controller.controller_name = '';
                v.edit_controller.location = '';
            },
            saveRfidController(){
                let v = this;
                v.saveDisable = true;
                Swal.fire({
                    title: 'Are you sure you want to save this controller?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('controller_id', v.controller.controller_id ? v.controller.controller_id : "");
                        formData.append('controller_name', v.controller.controller_name ? v.controller.controller_name : "");
                        formData.append('location', v.controller.location ? v.controller.location : "");
                        axios.post(`/save-rfid-settings-controller`, formData)
                        .then(response =>{
                            if(response.data.status == "saved"){
                                v.controllers.unshift(response.data.controller);   
                                Swal.fire('New RFID Controller has been saved.', '', 'success');        
                                $('#add-rfid-controller-modal').modal('hide');
                                v.clearFieldsRfidController();
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
                v.loading = true;
                v.controllers = [];
                axios.get('/get-rfid-settings-controllers-data')
                .then(response => { 
                    v.controllers = response.data;
                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                    v.loading = false;
                })
            },
            setPageController(pageNumber) {
                this.currentPageController = pageNumber;
            },
            resetStartRowController() {
                this.currentPageController = 0;
            },
            showPreviousLinkController() {
                return this.currentPageController == 0 ? false : true;
            },
            showNextLinkController() {
                return this.currentPageController == (this.totalPagesController - 1) ? false : true;
            },
        },
        computed: {
            filteredControllers(){
                let v = this;
                if(v.controllers){
                    return Object.values(v.controllers).filter(controller => {
                        return controller.location.toLowerCase().includes(this.keywords.toLowerCase()) || controller.controller_name.toLowerCase().includes(this.keywords.toLowerCase()) 
                    });
                }else{
                    return [];
                }
               
            },
            totalPagesController() {
                return Math.ceil(Object.values(this.filteredControllers).length / this.itemsPerPageController)
            },
            filteredControllerQueues() {
                var index = this.currentPageController * this.itemsPerPageController;
                var queues_array = this.filteredControllers.slice(index, index + this.itemsPerPageController);

                if(this.currentPageController >= this.totalPagesController) {
                    this.currentPageController = this.totalPagesController - 1
                }

                if(this.currentPageController == -1) {
                    this.currentPageController = 0;
                }

                return queues_array;
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>