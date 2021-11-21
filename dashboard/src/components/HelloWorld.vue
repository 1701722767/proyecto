<template>

  <el-button> Button for test 2 </el-button>

</template>

<script>
import { ElButton } from 'element-plus'
import 'element-plus/dist/index.css'

export default {
  name: 'HelloWorld',
  props: {
    msg: String
  },
  components:{
    ElButton
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
