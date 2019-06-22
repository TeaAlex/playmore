export default function createElemWithClasses(elText, classes) {
  
  const elem = document.createElement(elText);
  const classesArray = classes.split(' ');
  classesArray.forEach((cl) => {
    elem.classList.add(cl);
  });
  
  return elem;
}