<script setup>
import {loginWithGoogleAPICallback,registerWithGoogleAPICallback} from '@/api'
import {fetchGETWithParams} from '@/logic/fetchAPI'
import {getAllQueryFromUrl} from '../logic/url'
import { userAuthenticatedProcess,isLoginAction,isRegisterAction,redirectToEmailExistPage, userRegisterThirdPartyProcess, redirectToEmailNotExistPage } from '../logic/auth'
import { googleLoginAPI, googleRegisterAPI } from '../api';
import { fetchPOST } from '../logic/fetchAPI';
(async function (){
    // if(isLoginAction()){
    //     const response=await fetchGETWithParams(googleLoginAPI,getAllQueryFromUrl());
    //     if(response.user&&response.token){
    //         userAuthenticatedProcess(response.user,response.token);
    //     }else{
    //         redirectToEmailNotExistPage();
    //     }
    // }
    const query=getAllQueryFromUrl();
    const response=await fetchPOST(googleRegisterAPI,{
        code:query.code,
        email:'mn.bf.thien@gmail.com',
        password:'onlyone2402!!',
        username:'ngo_thien',
    });
    console.log(response);
        // if(response.user&&response.token){
        //     userAuthenticatedProcess(response.user,response.token);
        // }else{
        //     redirectToEmailNotExistPage();
        // }
    // console.log(response);
    // if(isRegisterAction()){
    //     const response=await fetchGETWithParams(registerWithGoogleAPICallback,getAllQueryFromUrl());
    //     console.log(getAllQueryFromUrl());
    //     if(response.email){
    //         userRegisterThirdPartyProcess(response.email,response.token);
    //     }else{
    //         redirectToEmailExistPage();
    //     }
    // }
}());
</script>
<template>
    <div>Đang xử lý...</div>
</template>