export const fetchPOST=async (api,data=null)=>{
    const response=await fetch(api,{
        method:'POST',
        body:JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json; charset=UTF-8',
        },
    });
    return response.json();
}
export const fetchGETWithParams=async (api,data) => {
    const query=new URLSearchParams(data).toString();
    const response=await fetch(api+'?'+query,{
        headers:{
            'Content-Type': 'application/json',
            'Accept': 'application/json; charset=UTF-8',
        },
    });
    return response.json();
}
export const fetchGET=async(api) => {
    const response=await fetch(api,{
        headers:{
            'Accept': 'application/json; charset=UTF-8',
        },
    });
    return response.json();
}