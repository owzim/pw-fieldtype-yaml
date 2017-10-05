# Fieldtype YAML

---

#### for ProcessWire 2.5.5 and 3.x

Field that stores YAML data and formats it as an object, when requested.

## Setup

After installation create a new `field`, let's say called `people` and assign it to a `template`, or just edit an existing text-based `field` and choose `Yaml` for the `type`, save!

In the `Details`-Tab you have some options you can choose from:

**Parse as**

* **`Associative Array:`** Default output by the used **Spyc** parser
* **`Object:`** Same as `Associative Array` but with `$foo->bar` instead of `$foo['bar']`
* **`WireArray/WireData`** You can access properties like you are used to with *pages* or *fields*, like `$page->person->get('title|name')` or `$page->people->find('age>20')`
* **`WireArray`** Same as `WireArray/WireData` but will wrap a `WireArray` around if the result is `WireData`



## Usage

Now, in your just created field you can put in some YAML like this:

```YAML
- name: Jane Doe
  occupation: Product Manager
  age: 33
  hobbies:
    - running
    - movies
- name: John Doe
  occupation: Service Worker
  age: 28
  hobbies:
    - cycling
    - fishing

```

In your template, or wherever you are accessing the page, you would use it like any other ProcesssWire data (if you set the parse option to either `WireData` or `Object`):

```PHP
$out = '';
foreach ($page->people as $person) {
   $out .= "Name: {$person->name} <br>";
   $out .= "Occupation: {$person->occupation} <br>";
   $out .= "Age: {$person->age} <br>";
   $out .= "Hobbies: <br>";
   foreach ($person->hobbies as $hobby) {
      $out .= "- {$hobby} <br>";
   }
   $out .= "--- <br>";
}
echo $out;
```

### More info about YAML:

* [Complete idiot's introduction to YAML](https://github.com/Animosity/CraftIRC/wiki/Complete-idiot%27s-introduction-to-yaml)
* [Specification](http://yaml.org/spec/1.0/)
* [Wikipedia](http://en.wikipedia.org/wiki/YAML)

### Acknowledgements

* I've used a namespaced version of the Autoloader class from [Template Data Providers](https://github.com/marcostoll/processwire-template-data-providers)
* The YAML parser is a namespaced version of [Spyc](https://github.com/mustangostang/spyc)

### Change Log

* **0.5.0** Update Spyc to 0.6.2
* **0.4.0**
  * Add `WireArray` `parseAs`-option
  * Empty fields will now default to an empty version of their respective `parseAs` objects
* **0.3.0** Make the module compatible with ProcessWire 3.x
* **0.2.0** add WireArray feature, parse chaching and make default `toString` output the name or label of the field, if WireData/-Array is selected
* **0.1.0** initial version
