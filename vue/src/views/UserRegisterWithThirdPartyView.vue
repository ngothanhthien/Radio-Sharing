<script setup>
import { reactive, unref, ref } from "vue";
import {fetchPOST} from "../logic/fetchAPI"
import {validateConfirmPassword} from "../logic/validate"
import {getThirdPartyEmail, userAuthenticatedProcess} from "../logic/auth"
import {registerThirdPartyAPI} from "../api"
import ReplyIcon from "../components/icons/ReplyIcon.vue"

const registerForm = reactive({
  password: "",
  username:"",
  confirmPassword: "",
});
const email=ref(getThirdPartyEmail());
const error = ref("");
async function submitForm(event) {
  const button = event.target;
  const enableMessage="Đăng ký";
  const disabledMessage ="Đăng ký...";
  disabledButton(button,disabledMessage);
  if(validateForm()){
    const response = await fetchPOST(registerThirdPartyAPI, unref(registerForm));
    if(response.error||response.failed){
        error.value="Có lỗi xảy ra";
      enableButton(button,enableMessage);
      return;
    }
    console.log(response.user);
    // userAuthenticatedProcess(response.user,response.token);
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
        <div class="mb-10">
            <div class="text-3xl font-bold">Đăng ký</div>
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
                        :placeholder="email"
                        disabled
                        />
                    </div>
                </div>
            </div>
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
