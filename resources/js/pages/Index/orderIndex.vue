<template>
    <div>
        <b-container>
            <Widget class="mx-auto" customHeader>
                <Alert :message="error" :type="alertType"/>
                <div v-if="orders.length == 0" class="d-flex flex-row justify-content-center align-items-center">
                    <small><strong>No order yet.</strong></small>
                </div>
                <div v-if="orders.length > 1" class="d-flex flex-row justify-content-end align-items-center">
                    <b-button class="all" size="sm" variant="primary" @click.prevent="payForAll()">pay for all</b-button>
                    <b-button class="all" size="sm" variant="success" @click.prevent="updateAll()">update all</b-button>
                    <b-button class="all" size="sm" variant="danger" @click.prevent="cancelAll()">cancel all</b-button>
                </div>
                <b-row class="holder" v-for="order in orders" v-bind:key="order.order.id">
                    <b-col lg="2" md="10" sm="10">
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <b-img v-bind="mainProps" id="image-holder" :src="order.product.image" rounded="top" alt="Top-rounded image"/>
                        </div>
                    </b-col>
                    <b-col lg="9" md="10" sm="10">
                        <strong>{{ order.product.name }}</strong><br>
                        <span><strong>quantity</strong>: x{{ order.order.quantity }}</span><br>
                        <span><strong>amount</strong>: &#8358;{{ order.order.price }}</span><br>
                        <span><strong>request time</strong>: {{ order.order.updated_at | datetime }}</span><br><br>
                        <b-button v-if="!order.paid" size="sm" variant="primary" @click.prevent="pay(order.order.id)">pay</b-button>
                        <b-button size="sm" variant="success" @click.prevent="update(order.order.id)">update</b-button>
                        <b-button size="sm" variant="danger" @click.prevent="cancel(order.order.id)">cancel</b-button>
                    </b-col>
                </b-row>
            </Widget>
        </b-container>
        <div v-if="isLoading" class="loader d-flex flex-row align-items-center justify-content-center">
            <loader/>
        </div>
    </div>
</template>
<script>
import Loader from  "../../components/mixins/loaderMixin.vue";
import Widget from '../../components/Widget/Widget';
import Alert from  "../../components/mixins/alertMixin.vue";
export default {
    mixins:[Loader,Alert],
    components:{
        Widget
    },
    data() {
        return {
            error:null,
            orders:[],
            mainProps: { width: 70, height: 70, class: 'm1' }
        }
    },
    methods: {
        payForAll() {},
        updateAll() {},
        cancelAll() {},
        pay(order) {},
        update(order) {},
        cancel(order) {}
    },
    created() {
        //get ongoing orders
        this.isLoading = true;
        axios.get('/api/order/index')
        .then((res) => {
            this.isLoading = false;
            if(res.data.data.orders) {
                this.orders = res.data.data.orders;
            }
        })
        .catch((err) => {
            this.isLoading = false;
            this.alertType = "danger";
            this.error = err.response.data;
        }); 
    },
    filters: {
        datetime(value) {
            let week = {
                0:"Sunday",
                1:"Monday",
                2:"Tuesday",
                3:"Wednesday",
                4:"Thursday",
                5:"Friday",
                6:"Saturday"
            };

            let date = new Date(value);
            let dateString = "";
            dateString += date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
            dateString += ":";
            dateString += date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
            dateString += "     ";
            dateString += week[date.getDay()];
            dateString += "  ";
            dateString += date.getDate() < 10 ? '0'+ date.getDate() : date.getDate();
            dateString += "  ";
            dateString += date.getMonth() < 10 ? '0'+ (parseInt(date.getMonth()) + 1) : (parseInt(date.getMonth()) + 1);
            dateString += ', '+date.getFullYear();
            return dateString;
        }
    }
}
</script>
<style lang="scss" scoped>
@import '../../styles/app';
.holder{
    margin:10px;
    padding:5px;
}

.widget {
    max-width: 100%;
    padding: 10px !important;
    margin-left:0px !important;

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-weight: $font-weight-normal;
    }
  }
  .all{
      margin-right:10px;
  }
</style>