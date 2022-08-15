<script setup>
import { reactive, unref, ref } from "vue";
import {fetchGET, fetchPOST} from "../logic/fetchAPI"
import {validateConfirmPassword, validateEmail} from "../logic/validate"
import {setRegisterAction, userAuthenticatedProcess} from "../logic/auth"
import {registerAPI,registerWithGoogleAPI} from "../api"
import GoogleIcon from "../components/icons/GoogleIcon.vue"
import ReplyIcon from "../components/icons/ReplyIcon.vue"

const registerForm = reactive({
  email: "",
  password: "",
  username:"",
  confirmPassword: "",
});
const error = ref("");
async function registerByGoogle(){
  const response=await fetchGET(registerWithGoogleAPI);
  if(response.url){
    setRegisterAction();
    window.location.href = response.url;
  }
}
async function submitForm(event) {
  const button = event.target;
  const enableMessage="Đăng ký";
  const disabledMessage ="Đăng ký...";
  disabledButton(button,disabledMessage);
  if(validateForm()){
    const response = await fetchPOST(registerAPI, unref(registerForm));
    if(response.errors||response.failed){
        error.value="Có lỗi xảy ra";
      enableButton(button,enableMessage);
      return;
    }
    userAuthenticatedProcess(response.user,response.token);
  } 
  enableButton(button,enableMessage);
}
function disabledButton(button,message){
  button.disabled = true;
  button.innerHTML = "Đăng ký...";
}
function enableButton(button,message){
  button.disabled = false;
  button.innerHTML = "Đăng ký";
}
function validateForm() {
    if(!registerForm.username){
        error.value="Tên người dùng không được để trống";
        return false;
    }
  if (!registerForm.email) {
    error.value = "Email không được để trống";
    return false;
  }
  if (!validateEmail(registerForm.email)) {
    error.value="Email không hợp lệ";
    return false;
  }
  if (!registerForm.password) {
    error.value = "Password không được để trống";
    return false;
  }
  if(!registerForm.confirmPassword||!validateConfirmPassword(registerForm.password, registerForm.confirmPassword)) {
    error.value="Mật khẩu xác nhận không giống mật khẩu";
    return false;
  }
  return true;
}
function clearError() {
  error.value = '';
}
</script>
<template>
  <div class="absolute h-screen flex">
    <div class="hidden lg:block w-5/12 h-full">
      <img
        src="/login.webp"
        class="w-full object-cover relative top-1/2 -translate-y-1/2"
      />
    </div>
    <div class="w-full lg:w-7/12 py-24 relative">
      <div class="w-5/6 sm:w-1/2 mx-auto text-center">
        <div class="mt-10">
          <div class="text-2xl font-bold">Đăng ký bằng</div>
          <div class="flex justify-center">
            <div class="flex justify-center mt-3 mx-2">
              <button
                class="flex px-3 py-3 text-sm font-semibold border border-callToAction rounded-lg text-callToAction"
                @click="registerByGoogle"
              >
                <GoogleIcon class="w-5 h-5 fill-current" />
                <span class="ml-1">Google</span>
              </button>
            </div>
          </div>
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-blue-50">Hoặc</span>
            </div>
          </div>
        </div>
        <div class="text-left">
          <div class="text-red-500">
            {{ error }}
          </div>
        </div>
        <form @submit.prevent>
            <div class="my-3">
            <div>
              <div>
                <input
                  class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-callToAction"
                  placeholder="Tên người dùng"
                  v-model="registerForm.username"
                  @focus="clearError"
                />
              </div>
            </div>
          </div>
          <div class="my-3">
            <div class="">
              <div class="">
                <input
                  class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-callToAction"
                  placeholder="Email"
                  v-model="registerForm.email"
                  @focus="clearError"
                />
              </div>
            </div>
          </div>
          <div class="my-3">
            <div class="">
              <div class="">
                <input
                  type="password"
                  autocomplete="on"
                  class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-callToAction"
                  placeholder="Mật khẩu"
                  v-model="registerForm.password"
                  @focus="clearError"
                />
              </div>
            </div>
          </div>
          <div class="my-3">
            <div class="">
              <div class="">
                <input
                  type="password"
                  autocomplete="on"
                  class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-callToAction"
                  placeholder="Xác nhận mật khẩu"
                  v-model="registerForm.confirmPassword"
                  @focus="clearError"
                />
              </div>
            </div>
          </div>
          <div class="mt-6">
            <button
              @click="submitForm"
              class="inline-block disabled:bg-blue-400 disabled:cursor-default rounded-sm font-medium border border-solid cursor-pointer text-center text-base py-3 px-6 text-white bg-callToActionFill hover:bg-callToActionFillHover w-full"
            >
              Đăng ký
            </button>
          </div>
          <p class="mt-3 flex justify-end text-callToAction items-center">
            <div><ReplyIcon class="w-5 h-5 fill-current" /></div>
            <div><a href="/">Về trang đăng nhập</a></div>
          </p>
        </form>
      </div>
    </div>
  </div>
</template>
