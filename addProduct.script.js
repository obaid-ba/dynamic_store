document.getElementById('firstCat').addEventListener('change', function() {
  const selectedValue = this.value;
  selectedValue != ''? document.getElementById('secondCat').style.display = 'block' : document.getElementById('secondCat').style.display = 'none' ;
  if (selectedValue === 'informatique') {
    document.getElementById('secondCat').innerHTML = `
      <option value="Ordinateur Portable">Ordinateur Portable</option>
      <option value="Ordinateur Bureau">Ordinateur Bureau</option>
      <option value="Ecrans">Ecrans</option>
      <option value="Serveurs">Serveurs</option>
    `;
  } else if (selectedValue === 'gaming') {
    document.getElementById('secondCat').innerHTML = `
      <option value="Ordinateur Portable Gamer">Ordinateur Portable Gamer</option>
      <option value="Ordinateur De Bureau Gamer">Ordinateur De Bureau Gamer</option>
      <option value="Setup Gaming">Setup Gaming</option>
      <option value="Ecran Gamer">Ecran Gamer</option>
    `;
  } else if (selectedValue === 'Telephonie') {
    document.getElementById('secondCat').innerHTML = `
      <option value="Smartphone & Mobile">Smartphone & Mobile</option>
      <option value="Telephone Fixe">Telephone Fixe</option>
      <option value="Smartwatch">Smartwatch</option>
      <option value="Accessoires">Accessoires </option>
    `;
  } else {
    document.getElementById('secondCat').innerHTML = '';
  }
});