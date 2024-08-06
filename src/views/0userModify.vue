<template>
    <div>
        <h2>Update User Information</h2>
        <form @submit.prevent="submitForm" v-if="boolSessio">
            <div>
                <label for="username">User Name:</label>
                <input type="text" id="username" v-model="formData.username" required>
            </div>
            <div>
                <label for="email">User Email:</label>
                <input type="email" id="email" v-model="formData.email" required>
            </div>
            <div>
                <label for="role">User Role:</label>
                <select id="role" v-model="formData.role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" v-model="formData.password">
            </div>
            <div>
                <label for="verifyPassword">Verify Password:</label>
                <input type="password" id="verifyPassword" v-model="formData.verifyPassword">
            </div>
            <div>
                <label for="user_pic">User pic:</label>
                <input type="text" id="user_pic" v-model="formData.user_pic">
            </div>
            <div v-if="passwordMismatch" class="error">
                Passwords do not match!
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            userID: "",
            apikey: "",
            user_role: "",
            formData: {
                username: '',
                email: '',
                role: 'user',
                password: '',
                verifyPassword: '',
                user_pic: ""
            }
        }
    },
    computed: {
        passwordMismatch() {
            return this.formData.password && this.formData.verifyPassword && this.formData.password !== this.formData.verifyPassword;
        }
    },
    methods: {
        submitForm() {
            if (!this.passwordMismatch) {
                console.log('Form Data:', this.formData);
                axios.post("http://localhost/API/" + this.apikey + "." + this.userID + "." + this.user_role + "/ModifyUser/" + this.userID, {
                    data: [
                        {
                            "UserName": this.formData.username,
                            "UserEmail": this.formData.email,
                            "UserRole": this.formData.role,
                            "pass": this.formData.password,
                            "ProfilePic": this.formData.user_pic
                        }
                    ]
                }
                ).catch(error => {
                    // Capturar l'error i mostrar el missatge d'error a la pàgina
                    const message = error.response.data;
                    document.getElementById("divError").innerHTML = message;
                    console.log(`Error message: ${message}`);
                });
                //   alert('Form submitted successfully!');
            } else {
                alert('Please correct the errors in the form.');
            }
        },
        fetchUserData() {
            axios.get("http://localhost/API/" + this.apikey + "." + this.userID + "." + this.user_role + "/UserInfo/" + this.userID)
                .then(response => {
                    console.log(response.data[0])
                    this.formData.username = response.data[0].user_name;
                    this.formData.email = response.data[0].user_email;
                    this.formData.role = response.data[0].user_role;
                    this.formData.password = response.data[0].pass;
                    this.formData.verifyPassword = response.data[0].pass;
                    this.formData.user_pic = response.data[0].profile_pic;
                })
                .catch(error => {
                    console.log('Error fetching user data:', error);
                });
        },
        comprovarSessio() {
            if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.user_role) {
                this.userID = sessionStorage.UserID;
                this.apikey = sessionStorage.APIKEY;
                this.user_role = sessionStorage.user_role;
                
                this.boolSessio = true;
                
                return true;
            }
            else {
                this.boolSessio = false;
                return false;
            }
        }
    },
    
    created() {
        this.comprovarSessio();
        this.fetchUserData();
    }
}
</script>

<style scoped>
.error {
    color: red;
}
</style>