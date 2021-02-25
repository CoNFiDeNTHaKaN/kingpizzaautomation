@extends('templates.manager')

@section('pageTitle', 'Edit restaurant menu')

@section('content')
  <div id="edit-menu" class="d-none d-md-block">
<div class="wrapper">
  <h1>Eat Kebab Online menu manager</h1>

    <div class="edit-menu">
    <form class="edit-menu__menu">
    <div class="edit-menu__group" v-for="group in menu" v-bind:group="group.name.toLowerCase()">
      <div class="edit-menu__group__info has-anchor">
        <i class="anchor" v-bind:id="group.name.toLowerCase()"></i>
        <input required type="text" v-model="group.name" placeholder="Menu group name">
        <textarea v-model="group.description" placeholder="Description"></textarea>
      </div>
      <div class="edit-menu__group__item" v-for="(groupitem, i) in group.items" >
        <div class="edit-menu__group__item__info has-anchor">
            <i class="anchor" v-bind:id="groupitem.name.replaceAll(' ', '-').toLowerCase()"></i>
            <input required type="text" v-model="groupitem.name" placeholder="Item name">
            <number-two-decimals required v-model="groupitem.base_price"></number-two-decimals>
            <div class="edit-menu-input__adds">
              <span class="t-red" @click="deleteObject(group.items, groupitem)"><i class="fas fa-times-circle"></i> Item</span>
              <span class="t-blue" @click="copyItem(groupitem, i, group.items)"><i class="fas fa-clone"></i> Copy</span>
              <span class="t-green" @click="addItemSize(groupitem)"><i class="fas fa-plus-circle"></i> Size</span>
              <span class="t-green" @click="addItemOptionGroup(groupitem)"><i class="fas fa-plus-circle"></i> Option</span>
            </div>
        </div>

        <div class="edit-menu__group__item__description">
          <textarea v-model="groupitem.description" placeholder="Description"></textarea>
        </div>

        <template v-if="groupitem.sizes.length > 0">
          <h4>Items sizes <span class="t-green" @click="addItemSize(groupitem)"><i class="fas fa-plus-circle"></i> Add</span></h4>
          <div class="edit-menu__group__item__sizes">
            <div class="edit-menu__group__item__size" v-for="size in groupitem.sizes">

              <div class="edit-menu__group__item__size__label">
                <i class="far fa-times-circle t-red" @click="deleteObject(groupitem.sizes, size)"></i>
                <label>Label</label>
                <input required type="text" v-model="size.label">
              </div>
              <div class="edit-menu__group__item__size__price">
                <label>Price</label>
                <number-two-decimals v-model="size.additional_charge"></number-two-decimals>
              </div>
            </div>
          </div>
        </template>
        <div class="edit-menu__group__item__flags">
        </div>
        <template v-if="groupitem.options.length > 0">
          <h4>Item options <span class="t-green" @click="addItemOptionGroup(groupitem)"><i class="fas fa-plus-circle"></i> Add</span></h4>
          <div class="edit-menu__group__item__option" v-for="optiongroup in groupitem.options">
            <div class="edit-menu__group__item__option__group">
              <i class="far fa-times-circle t-red" @click="deleteObject(groupitem.options, optiongroup)"></i>
              &nbsp;
              <input required type="text" v-model="optiongroup.name" placeholder="Option name">
              <div class="edit-menu__group__item__option__group__attributes">
                <label>Allow multiple
                  <input type="checkbox" v-model="optiongroup.allow_multiple">
                  <input type="number" min="0" placeholder="Max." v-if="optiongroup.allow_multiple" v-model="optiongroup.multiple_limit">
                </label>
                <label>Required
                  <input type="checkbox" v-model="optiongroup.required">
                </label>
              </div>

            </div>
            <div class="edit-menu__group__item__option__group edit-menu-input__desc">
              <label>Description</label>
              <input type="text" v-model="optiongroup.description">
            </div>

            <table class="edit-menu__group__item__option__options">
              <thead>
                <tr>
                  <th>Label</th>
                  <th>Description</th>
                  <th>Additional cost</th>
                  <th>Pre-selected</th>
                </tr>
              </thead>
              <tbody>

              <tr v-for="optiongroupitem in optiongroup.option_values">
                <td>
                  <i class="far fa-times-circle t-red" @click="deleteObject(optiongroup.option_values, optiongroupitem)"></i>
                  <input required type="text" v-model="optiongroupitem.name">
                </td>
                <td>
                  <input type="text" v-model="optiongroupitem.description">
                </td>
                <td>
                  <number-two-decimals v-model="optiongroupitem.additional_charge"></number-two-decimals>
                </td>
                <td class="t-center">
                  <input type="checkbox" v-model="optiongroupitem.selected">
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <p class="t-center t-green"><b @click="addItemOption(optiongroup)"><i class="fas fa-plus-circle"></i> Add option value</b></p>
                </td>
              </tr>

              </tbody>

            </table>


          </div>
        </template>
      </div>
    </div>
</form>
  <div class="edit-menu__nav">
    <ul>
      <li v-for="group in menu">
        <div class="edit-menu__nav__group">
          <a v-bind:href="'#' + group.name.toLowerCase()">
            @{{ group.name }}
          </a>
          <i @click="deleteObject(menu, group, true)" class="fas fa-times-circle t-red"></i>
          <i @click="addItem(group)" class="t-green fas fa-plus-circle"></i>
          <i @click="moveGroupDown(group)" class="fas fa-arrow-alt-circle-down"></i>
          <i @click="moveGroupUp(group)" class="fas fa-arrow-alt-circle-up"></i>
        </div>
        <ul>
          <li v-for="item in group.items">
            <a v-bind:href="'#' + item.name.replaceAll(' ', '-').toLowerCase()">
              @{{ item.name }}
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <p class="t-green"><b @click="addGroup()"><i class="fas fa-plus-circle"></i> Add new group</b></p>

  </div>
  </div>

</div>
  <div class="submit-bar">
    <div class="wrapper">
      <a href="#" class="button button--green" @click="saveMenu">Save restaurant info</a>
    </div>
  </div>
</div>

<div class="d-md-none">
    <div class="wrapper">
        <h1>Eat Kebab Online menu manager</h1>
        <p><b>This view is not available on mobiles.</b></p>
    </div>
</div>
@endsection

@push('footerScripts')

<script>
  const editMenu = new Vue({
    el: '#edit-menu',
    data: {
      menu: @json($user->restaurant->menu)
    },
    methods: {
      moveGroupUp: function (group) {
        var index = this.menu.indexOf(group);
        var newIndex = (index - 1);
        var cacheGroup = JSON.parse(JSON.stringify(group));
        this.menu.splice(index, 1);
        this.menu.splice(newIndex, 0, cacheGroup);
      },
      moveGroupDown: function (group) {
        var index = this.menu.indexOf(group);
        var newIndex = (index + 1);
        var cacheGroup = JSON.parse(JSON.stringify(group));
        this.menu.splice(index, 1);
        this.menu.splice(newIndex, 0, cacheGroup);
      },
      deleteObject: function (group, item, confirmUser = false) {
          if (confirmUser) {
              if (!confirm('Are you sure you want to delete that? This is irreversible.')) {
                  return false;
              }
          }
        var index = group.indexOf(item);
        this.$delete(group, index);
      },
      addGroup : function() {
        this.menu.push({
          "description" : "",
          "id" : -1,
          "image" : "",
          "name" : "",
          "slug" : "",
          "items" : [{
            "base_price": 0,
            "description" : "",
            "name" : "New item",
            "flags" : [],
            "id" : -1,
            "options" : [],
            "sizes" : []
          }]
        });
        $newGroup = document.querySelector('.edit-menu__group:last-child');
        window.scrollTo( null, $newGroup.offsetTop);
        $newGroup.querySelector('.edit-menu__group__info input');
      },
      addItem : function(group) {
        group.items.push({
          "base_price": 0,
          "description" : "",
          "name" : "",
          "flags" : [],
          "id" : -1,
          "options" : [],
          "sizes" : []
        });

        $newItem = document.querySelector(`[group="${group.name.toLowerCase()}"] > :last-child`);
        window.scrollTo( null, $newItem.offsetTop);
        $newItem.querySelector('.edit-menu__group__info input');
      },
      addItemSize : function(item) {
        item.sizes.push({
          "label" : "",
          "price" : 0
        });
      },
      addItemOptionGroup : function(item) {
        item.options.push({
          "id":-1,
          "name":"New item",
          "description":"",
          "required":false,
          "allow_multiple":false,
          "option_values":[{
            "id":-1,
            "name":"",
            "description":"",
            "additional_charge":0,
            "selected":false
          }]
        })
      },
      addItemOption : function(itemOptionGroup) {
        itemOptionGroup.option_values.push({
          "id":-1,
          "name":"",
          "description":"",
          "additional_charge":0,
          "selected":false
        })
    },
        copyItem : function(itemData, index, groupItems) {
            let clonedItem = JSON.parse(JSON.stringify( itemData ));
            groupItems.splice(index, 0, clonedItem);
        },
        saveMenu: function() {
            let hasScrolled = false;
            let hasErrors = false;
            document.querySelectorAll('input[required]').forEach(function(el) {
                if (!el.checkValidity()){
                    if (!hasScrolled) {
                        hasScrolled = true;
                        hasErrors = true;
                        el.scrollIntoView();
                    }
                    el.classList.add('input--invalid');
                    console.log(el);
                }
            });
            if (hasErrors) {
                setTimeout(function() {
                    alert('The menu could not be saved. The form is missing some required fields, please check and try again.');
                }, 1000);
            } else {
                saveMenu = axios.post('{{ route('manager.editMenuSubmit') }}', {
                    menu: this.menu
                });
                saveMenu.then(function(response) {
                    if (response.status === 200) {
                        alert('Menu saved successful.');
                    } else {
                        alert('Something went wrong, please check and try again.');
                    }
                });
            }
        }
    },
    watch: {
      customerOrderItems: function() {
        this.pingBasket();
      }
    },
    mounted: function() {

    }
  });
</script>
@endpush
