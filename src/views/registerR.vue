<template>
    <div>
        <h2>Register</h2>
        <form @submit.prevent="submitForm">
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
                <input type="password" id="password" v-model="formData.password" required>
            </div>
            <div>
                <label for="verifyPassword">Verify Password:</label>
                <input type="password" id="verifyPassword" v-model="formData.verifyPassword" required>
            </div>
            <div>
                <label for="user_pic">User pic:</label>
                <input type="text" id="user_pic" v-model="formData.user_pic">
            </div>
            <div v-if="passwordMismatch" class="error">
                Passwords do not match!
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
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
                // Aquí pots afegir la lògica per enviar les dades a l'API
                console.log('Form Data:', this.formData.username);

                axios.post('http://localhost/API/register', {
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

                // alert('Form submitted successfully!');
            } else {
                alert('Please correct the errors in the form.');
            }

            this.$router.push('/login');
        }
    }
}
</script>

<style scoped>
.error {
    color: red;
}
</style>