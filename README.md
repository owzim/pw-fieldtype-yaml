# Fieldtype YAML

---

#### for ProcessWire 2.5

Field that stores YAML data and formats it as an object, when requested.

## Setup

After installation create a new `field`, let's say called `people` and assign it to a `template`, or just edit an existing text-based `field` and choose `Yaml` for the `type`, save!

In the `Details`-Tab you have some options you can choose from:

**Parse as**

Default is `WireArray`, the data can also be parsed as `Object` or `Associative Array`.

*Associative Array* is the fastest and the default output by the used *Spyc* parser, *WireArray* might be the slowest, but it's also the most feature rich. You can access properties like you are used to with *pages* or *fields*, like `$someObject->get('headline|title')`.

**Font Family**

The font stack used for the `Textarea`, default is `Consolas, Monaco, Andale Mono, monospace`. Since we write YAML in here, a monospace font makes sense.

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

* **0.1.0** initial version

