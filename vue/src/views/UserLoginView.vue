<script setup>
import { reactive, unref, ref } from "vue";
import router from "@/router"
import { fetchPOST,fetchGET } from "@/logic/fetchAPI";
import { validateEmail } from "@/logic/validate";
import { loginAPI,loginWithGoogleAPI } from "@/api";
import {setLoginAction,userAuthenticatedProcess} from "@/logic/auth"
import GoogleIcon from "@/components/icons/GoogleIcon.vue";
const loginForm = reactive({
  email: "",
  password: "",
}); 
const error = ref("");
async function submitForm(event) {
  const button = event.target;
  const enableMessage="Đăng nhập";
  const disabledMessage ="Đăng nhập..."
  disabledButton(button,disabledMessage);
  if(validateForm()){
    const response = await fetchPOST(loginAPI, unref(loginForm), "POST");
    if(response.errors){
      enableButton(button,enableMessage);
      return;
    }
    if(response.failed){
      error.value=response.failed;
      enableButton(button,enableMessage);
      loginForm.password='';
      return;
    }
    userAuthenticatedProcess(response.user,response.token);
  }
  enableButton(button,enableMessage);
}
function disabledButton(button,message){
  button.disabled = true;
  button.innerHTML = "Đăng nhập...";
}
function enableButton(button,message){
  button.disabled = false;
  button.innerHTML = "Đăng nhập";
}
function validateForm() {
  if (!loginForm.email) {
    error.value = "Email không được để trống";
    return false;
  }
  if (!validateEmail(loginForm.email)) {
    error.value="Email không hợp lệ";
    return false;
  }
  if (!loginForm.password) {
    error.value = "Password không được để trống";
    return false;
  }
  return true;
}
function clearError() {
  error.value = '';
}
async function loginByGoogle(){
  const response= await fetchGET(loginWithGoogleAPI);
  if(response.url){
    setLoginAction();
    window.location.href = response.url; 
  }
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
          <div class="text-2xl font-bold">Đăng nhập bằng</div>
          <div class="flex justify-center">
            <div class="flex justify-center mt-3 mx-2">
              <button
                @click="loginByGoogle"
                class="flex px-3 py-3 text-sm font-semibold border border-callToAction rounded-lg text-callToAction"
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
            <div class="">
              <div class="">
                <input
                  class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-callToAction"
                  placeholder="Email"
                  v-model="loginForm.email"
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
                  v-model="loginForm.password"
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
              Đăng nhập
            </button>
          </div>
          <div class="mt-2 text-right text-textDisable">
            <a href="#">Quên mật khẩu?</a>
          </div>
          <p class="mt-3">
            Chưa có tài khoản?
            <a href="/register" class="text-callToAction">Đăng ký ngay</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</template>
