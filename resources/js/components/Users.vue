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
                        <h2 class="text-white font-weight-bold my-2 mr-5">Users</h2>
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
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">User Roles</a>
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
                            <div class="col-sm-4 mt-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer">
                                            <i class="fas fa-search text-dark-50"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search User" v-model="keywords">
                                </div>
                            </div>
                            <div class="col-md-3 mt-5">
                                <div class="form-group">
                                    <select class="form-control form-control-primary" v-model="filterRole" id="filterRole">
                                        <option value="">Filter by Role</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Manager">Manager</option>
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <!-- <th>User ID</th> -->
                                        <th class="text-center">User</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, i) in filteredUserQueues" :key="i" >
                                       <td><small>{{user.name}}</small></td>
                                       <td><small>{{user.email}}</small></td>
                                       <td class="text-center">
                                           <a href="#" @click="setUserRole(user)">
                                                <small>{{user.user_role ? user.user_role.role : "User" }}</small>
                                           </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row col-md-12" v-if="filteredUserQueues.length">
                            <div class="col-6">
                                <button :disabled="!showPreviousLinkUser()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageUser(currentPageUser - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPageUser + 1 }} of {{ totalPagesUser }}</span>
                                <button :disabled="!showNextLinkUser()" class="btn btn-default btn-sm btn-fill" v-on:click="setPageUser(currentPageUser + 1)"> Next </button>
                            </div>
                            <div class="col-6 text-right">
                                <span class="mr-2">Total Users : {{ filteredUsers.length }} </span><br>
                            </div>
                        </div>

                        <div class="row" v-if="users.length == 0">
                            <div class="col-md-3 pt-5 pb-5">
                                <div class="spinner spinner-left spinner-primary spinner-lg">
                                    <span class="ml-15">Loading.. Please wait..</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  


            </div>  
        </div>  

    </div>  

    <div class="modal fade" id="change-user-role-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Change User Role</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">User Role</label> 
                                <select name="select" v-model="user.role" class="form-control">
                                    <option value="">Select Role</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Manager">Manager</option>
                                    <option value="President">President</option>
                                    <option value="User">User</option>
                                </select>
                                <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveChangeUserRole">Save</button>
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
                filterRole : 'Administrator',
                user : [],
                users : [],
                errors : [],
                currentPageUser: 0,
                itemsPerPageUser: 10, 
            }
        },
        created () {
            this.getUsers();
        },
        methods: {
            saveChangeUserRole(){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to change role for this user?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('user_id', v.user.id ? v.user.id : "");
                        formData.append('role', v.user.role ? v.user.role : "");

                        axios.post(`/save-change-user-role`, formData)
                        .then(response =>{
                            if(response.data == "saved"){
                                v.getUsers();
                                Swal.fire('User role has been changed!', '', 'success');        
                                $('#change-user-role-modal').modal('hide');
                                v.user = [];
                                
                            }else{
                                Swal.fire('Error: Cannot changed. Please try again.', '', 'error');   
                                v.employeePositionCode.position_code = "";
                            }
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                        })
                    }   
                })
            },
            setUserRole(user){
                let v = this;
                v.user = user;
                v.user.role = user.user_role ? user.user_role.role : "User"; 
                $('#change-user-role-modal').modal('show');
            },
            getUsers() {
               let v = this;
               v.users = [];
               axios.get('/get-users-data')
                .then(response => { 
                    v.users = response.data;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            setPageUser(pageNumber) {
                this.currentPageUser = pageNumber;
            },
            resetStartRowUser() {
                this.currentPageUser = 0;
            },
            showPreviousLinkUser() {
                return this.currentPageUser == 0 ? false : true;
            },
            showNextLinkUser() {
                return this.currentPageUser == (this.totalPagesUser - 1) ? false : true;
            },
        },
        computed: {
            filteredUsers(){
                let self = this;
                if(self.users){
                    return Object.values(self.users).filter(user => {
                        if(self.filterRole){
                            if(user.user_role){
                                return user.user_role.role.toLowerCase().includes(this.filterRole.toLowerCase())
                            }
                        }else{
                            return user.name.toLowerCase().includes(this.keywords.toLowerCase()) || user.email.toLowerCase().includes(this.keywords.toLowerCase())
                        }
                    });
                }else{
                    return [];
                }
               
            },
            totalPagesUser() {
                return Math.ceil(Object.values(this.filteredUsers).length / this.itemsPerPageUser)
            },
            filteredUserQueues() {
                var index = this.currentPageUser * this.itemsPerPageUser;
                var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPageUser);

                if(this.currentPageUser >= this.totalPagesUser) {
                    this.currentPageUser = this.totalPagesUser - 1
                }

                if(this.currentPageUser == -1) {
                    this.currentPageUser = 0;
                }

                return queues_array;
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>