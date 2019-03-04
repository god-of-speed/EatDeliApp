<template>
     <div>
        <div id="spinner" class="d-flex flex-row align-items-center justify-content-center">
            <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
     </div>
</template>
<script></script>
<style scoped>
    .text-success{
        color: green !important;
    }
    #spinner {
        width:100%;
        height:100%;
    }
    .spinner-border {
        margin:0 auto;
    }
</style>