



<div class="flex justify-end ">
  <div class="w-1/5">

  </div>
  <div class="w-2/5 bg-white px-10 py-[100px]">


    <div class="flex justify-between  pb-5">
      <p class="text-xl font-bold">Адрес за доставка </p>
      <p>Имате акаунт ? Вход</p>
    </div>

    <div x-data="{shipTo: 'test', wantInvoice: false}">
      <form >

        <div >
          <div class="border-y border-neutral-300 py-3 flex items-center gap-3" >

            <label class="text-gray-700 w-full" @click="shipTo = 'address'">
              <input type="radio" name="shippingTo" value="1" class="scale-150"/>
              <span class="ml-1">Доставка до Адрес</span>
            </label>

          </div>
          <div x-show="shipTo == 'address'" x-transition:enter.duration.500ms  x-transition:leave.duration.50ms x-cloack class="p-10 bg-neutral-100">
            <div class="grid grid-cols-2 gap-3">

              <div class="">
                <label for="" >Име </label>
                <input type="text" name="first_name" :value="data.user.first_name" class=" border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div class="">
                <label for="">Фамилия </label>
                <input type="text" name="last_name"  :value="data.user.last_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Имейл </label>
                <input type="text" name="email" :value="data.user.email" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Телефон </label>
                <input type="text" name="phone" :value="data.user.phone" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>

              <template x-if="wantInvoice" x-transition="" x-clock="">
                <div class="col-span-2">

                  <input type="hidden" name="wantInvoice" value="1">

                  <div class="grid grid-cols-2 gap-3 ">
                    <div class="">
                      <label for="">Компания </label>
                      <input type="text" name="invoice_company" :value="data.user.company.name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>


                    <div class="">
                      <label for="">Град </label>
                      <input type="text" name="invoice_city" :value="data.user.company.city" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="">
                      <label for="">Адрес </label>
                      <input type="text" name="invoice_address" :value="data.user.company.address" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>
                    <div class="">
                      <label for="">Телефон</label>
                      <input type="text" name="invoice_phone" :value="data.user.company.phone" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="">
                      <label for="">ЕИК </label>
                      <input type="text" name="invoice_vat" :value="data.user.company.vat" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <div class="flex items-end my-3">
                      <div class="flex items-center h-5">
                        <input type="checkbox" :value="data.user.company.vat_registered" :checked="data.user.company.vat_registered == 1" name="vat_registered" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150 mr-2" value="" checked="checked">
                      </div>
                      <label class="ml-2 text-basefont-medium text-gray-900 dark:text-gray-300">Регистрирана по ДДС</label>
                    </div>
                  </div>

                </div>
              </template>

              <div class="col-span-2">
                <div class="flex mb-2">
                  <input type="text" id="address" name="address" value="" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  <button type="button" id="location" class="border border-gray-300 bg-white text-gray-900 text-sm rounded-lg block w-fit px-5 " name="button"><i class="fa-solid fa-crosshairs text-[20px]"></i></button>
                </div>
              </div>

              <div class="col-span-2  h-[250px]" >
                <div class="" id="map"></div>
              </div>


              <div class="col-span-2 grid grid-cols-3 gap-3">

              <div>
                <label for="">Държава </label>
                <input type="text" name="country" id="country" :value="selectedAddress.country" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>

              <div>
                <label for="">Град </label>
                <input type="text" name="city" id="city" :value="selectedAddress.city" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>

              <div>
                <label for="">Пощенски код </label>
                <input type="text" name="post_code" id="post_code" :value="selectedAddress.post_code" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>

              <div class="col-span-2">
                <label for="">Улица </label>
                <input type="text" name="streetName" id="streetName" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Улица  № </label>
                <input type="text" name="streetNumber" id="streetNumber" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>


            </div>

            </div>



            <div class="flex items-center mt-5">
              <div class="flex items-center h-5">
                <input type="checkbox" value="" name="terms" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150 ">
              </div>
              <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Съгласен съм с <a href="/bg/terms-conditions" class="text-blue-600 hover:underline dark:text-blue-500">Общите Условия</a>.</label>
            </div>

            <div class="flex items-center  mt-5">
              <div class="flex items-center h-5">
                <input type="checkbox" value="" name="terms" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150  ">
              </div>
              <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Съгласен съм с <a href="/bg/gdpr" class="text-blue-600 hover:underline dark:text-blue-500">Политика за поверителност</a>.</label>
            </div>


          </div>
        </div>

        <div>
          <div class="border-b border-neutral-300 py-3 flex items-center gap-3"  >
            <label class="text-gray-700 w-full" @click="shipTo = 'addressEcont'">
              <input type="radio" name="shippingTo" value="2" class="scale-150"/>
              <span class="ml-1">Доставка до Офис</span>
            </label>


          </div>
          <div x-show="shipTo == 'addressEcont'" x-transition:enter.duration.500ms  x-transition:leave.duration.50ms x-cloack class="bg-neutral-100 p-10">
            <div class="grid grid-cols-2 gap-3">
              <div >
                <label for="">Име </label>
                <input type="text" name="first_name" :value="data.user.first_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Фамилия </label>
                <input type="text" name="last_name" :value="data.user.last_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Имейл </label>
                <input type="text" name="email" :value="data.user.email" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>
              <div>
                <label for="">Телефон </label>
                <input type="text" name="phone" :value="data.user.phone" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              </div>


            </div>
            <template x-if="wantInvoice" x-transition="" x-clock="">
              <div class="">

                <input type="hidden" name="wantInvoice" value="1">

                <div class="grid grid-cols-2 gap-3 ">
                  <div class="">
                    <label for="">Компания </label>
                    <input type="text" name="invoice_company" :value="data.user.company.name" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  </div>

                  <div class="">
                    <label for="">Град </label>
                    <input type="text" name="invoice_city" :value="data.user.company.city" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  </div>
                  <div class="">
                    <label for="">Адрес </label>
                    <input type="text" name="invoice_address" :value="data.user.company.address" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  </div>
                  <div class="">
                    <label for="">Телефон</label>
                    <input type="text" name="invoice_phone" :value="data.user.company.phone" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  </div>

                  <div class="">
                    <label for="">ЕИК </label>
                    <input type="text" name="invoice_vat" :value="data.user.company.vat" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                  </div>

                  <div class="flex items-end my-3">
                      <div class="flex items-center h-5">
                        <input type="checkbox" :value="data.user.company.vat_registered" :checked="data.user.company.vat_registered == 1" name="vat_registered" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150 mr-2" value="" checked="checked">
                      </div>
                      <label class="ml-2 text-basefont-medium text-gray-900 dark:text-gray-300">Регистрирана по ДДС</label>
                    </div>
                </div>

              </div>
            </template>

            <div class="flex items-center mt-5">
              <div class="flex items-center h-5">
                <input type="checkbox" value="" name="terms" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150 ">
              </div>
              <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Съгласен съм с <a href="/bg/terms-conditions" class="text-blue-600 hover:underline dark:text-blue-500">Общите Условия</a>.</label>
            </div>

            <div class="flex items-center  mt-5">
              <div class="flex items-center h-5">
                <input type="checkbox" value="" name="terms" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150  ">
              </div>
              <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Съгласен съм с <a href="/bg/gdpr" class="text-blue-600 hover:underline dark:text-blue-500">Политика за поверителност</a>.</label>
            </div>


          </div>
        </div>


        <div class="flex items-center mt-5">
          <div class="flex items-center h-5">
            <input type="checkbox" value="1" @click="wantInvoice =! wantInvoice" name="wantInvoice" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 scale-150 ">
          </div>
          <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Желая фактура на юридическо лице</label>
        </div>

        <div class="py-4">
          <lebal class="">Начин на плащане</p>

          <div class="flex items-center my-2">
            <input id="link-radio" type="radio" name="payment_method" checked="" value="code" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="link-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Наложен платеж</label>
          </div>

          <div class="flex items-center my-2">
            <input id="link-radio" type="radio" value="paypal" name="payment_method" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="link-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paypal</label>
          </div>

        </div>



            <div class="w-1/2">
                <button @click="alpineListeners('Shop.post_orders', $event)" name="button" class=" w-full py-2 bg-neutral-700 text-white hover:bg-neutral-100 hover:text-neutral-700 border-2 border-neutral-600">Заварши поръчката</button>
            </div>

      </form>
    </div>


  </div>
  <div class="w-2/5 bg-neutral-100 py-[50px]">
    <div  class="mt-8" apidata="Shop.get_cart" >
      <div class="flow-root">

        <div  class="divide-y divide-gray-200 w-4/5 px-10">



          <template x-for="product in data.cart.products">

            <div class="flex py-3 my-1 justify-between">
              <div class="flex items-center">
                <button type="button" @click="alpineListeners('Shop.delete_cart', $event)" keyname="cart" :data-id="product.id" class="font-medium text-slate-600 hover:text-indigo-500 px-3"><i class="fa-solid fa-xmark"></i></button>
                <img :src="product.images[0].image" alt="" width="100px" height="100px" class="object-cover">
              </div>


              <div class=" flex flex-col justify-around max-w-[50%]">
                <p class="mt-1 text-base text-gray-500 line-clamp-2 font-bold" :title="product.title" x-text="product.title">Salmon</p>
                <div class="flex">
                  <p x-text="product.quantity" class="text-slate-500 text-sm"></p>
                  <p class="text-slate-500 text-sm">x</p>
                  <p x-text="product.variation.selling_price" class="text-slate-500 text-sm">$90.00</p>
                  <p x-text="product.variation.currency" class="text-slate-500 text-sm"></p>
                </div>
              </div>
              <div class="flex items-center">
                <p x-text="product.total + ' ' + product.variation.currency" class="shrink-0 text-base text-gray-500"></p>
              </div>
            </div>



          </template>



          <div class="border-t border-gray-200 py-5">
            <input type="text" name="comment" id="comment" placeholder="Напиши бележка" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
          </div>


          <div class="py-3  divide-y divide-gray-200">

            <div class="py-2 flex justify-between text-base font-medium text-slate-600">
              <p>Междинна сума</p>
              <p x-text="data.cart.subtotal_without_disc + ' ' + data.cart.products[0].variation.currency" class="font-bold text-base">262 лв.</p>
            </div>
            <div class="py-2 flex justify-between text-base font-medium text-slate-600">
              <p>Отстъпка</p>
              <p x-text="data.cart.discount + ' ' + data.cart.products[0].variation.currency" class="font-bold text-base">262 лв.</p>
            </div>
            <div class="py-2 flex justify-between text-base font-medium text-slate-600">
              <p class="text-xl font-bold">Общо</p>
              <p x-text="data.cart.total.toFixed(2) + ' ' + data.cart.products[0].variation.currency" class="font-bold text-lg">262 лв.</p>
            </div>

          </div>



        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="/assets/plugins/google_map/geolocation.css">
<script src="/assets/plugins/google_map/geolocation.js" charset="utf-8"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXv2aq9j2fYGgYkqac9QP41zqOTJRmWB8&callback=initMap&libraries=places&v=weekly&language=bg" defer></script>







<div id="main" class="is-wrapper flex justify-center  " style="min-height:800px;">
  <?php echo  Page::html_res_change($page->html, '10x10'); ?>
  <?php echo  '<div x-init="callBackMain()"></div>' ?>
</div>
