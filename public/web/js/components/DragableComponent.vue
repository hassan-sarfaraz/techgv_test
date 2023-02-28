<template>
 <div >
   <h1>Vue Component</h1>
<draggable v-model="product_section_orderss" @change="update">
  <div class="form-group" v-for="product_section_order in product_section_orderss">
      <label for="name" class="col-sm-2 col-md-3 control-label">flash sale Order</label>
      <div style="border:1px solid grey;"class="col-sm-10 col-md-4">
          <li>{{product_section_order['name']}}</li>
          <li>{{product_section_order['order']}}</li>
          <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;"></span>
          <span class="help-block hidden"></span>
      </div>
  </div>
</draggable>
</div>
</template>

<script>
import draggable from 'vuedraggable'

    export default {
      components: {
        draggable,
        },
        props: [
          'product_section_orders'
        ],
        data() {
          return {
              product_section_orderss : this.product_section_orders
          }
        },

        methods: {
           update(){
             this.product_section_orderss.map((product_section_orders, index) => {
               product_section_orders.order = index + 1;
             })

             axios.post('reorder', {

               product_section_orders : this.product_section_orderss

             }).then((response) => {

             })
           }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
