<style>
  /*modal window styles*/
  .modal_window{
    position: absolute;
    width: 100vw;
    height: 100vh;
    z-index: 2;
    display: none;
  }
  .w_shadow{
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    transition: all 0.5s ease;
  }
  .w_shadow:hover{
    background: rgba(0, 0, 0, 0.4);
  } 
  .widnow{
    position: absolute;
    background: white;
    width: 500px;
    height: 365px;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50px;
    box-shadow: 0px 13px 34px 0px rgba(0, 0, 0, 0.35);
  }
  .w_text{
    position: absolute;
    width: calc(100% - 5px);
    max-height: 50%;
    overflow-y: auto;
    text-align: center;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
  .w_text::-webkit-scrollbar-track{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
  }
  .w_text::-webkit-scrollbar{
    width: 6px;
    background-color: #F5F5F5;
  }
  .w_text::-webkit-scrollbar-thumb{
    background-color: dimgray;
  }
  .w_text p{
    font-size: 1.8em;
    padding: 5px;
    transition: all 0.5s ease-out;
  }
  .widnow i{
    text-align: center;
    width: 100%;
    padding-top: 15px;
    padding-bottom: 15px;
    font-size: 3em;
    color: #6D89D5;
    background: rgba(109, 137, 213, 0.5);
    border-bottom: 2px solid #6D89D5;
  }
  .w_text p:hover{
    background: rgba(109, 137, 213, 0.5);
  }
  .w_close{
    position: absolute;
    right: 3vw;
    top: 3vw;
  }
  .w_close i{
    font-size: 2em;
    cursor: pointer;
    color: #6D89D5;
    transition: all 0.5s ease;
  }
  .w_close i:hover{
    color: #FF9A40;
  }
  .window_confirm{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 200px;
    height: 200px;
    display: none;
  }
  .window_confirm i:hover{
    color: #66E275
  }
  @media (min-width:0px) and (max-width:660px){
    .widnow{
      width: 350px;
      height: 265px;
    } 
    .widnow i{
      font-size: 2em;
    }
    .w_text p{
      font-size: 1.5em;
    }
  }
  @media (min-width:0px) and (max-width:450px){
    .widnow{
      width: 300px;
      height: 265px;
    }
  }
  @media (min-width:0px) and (max-width:400px){
    .widnow{
      width: 250px;
      height: 265px;
    }
    .w_text p{
      font-size: 1.2em;
    }
  }
</style>
<div class="modal_window">
  <div class="w_shadow"></div>
  <div class="widnow">
    <i class="im im-megaphone"></i>
    <div class="w_text">
      <p>Текст</p>
    </div>
  </div>
  <div class="w_close"><i class="im im-x-mark-circle-o"></i></div>
</div>