class ClassClick {

  constructor() {

    const boton_click = document.getElementById('boton_click');

    boton_click.addEventListener("click", () => {

      this.click();
    });
  }
  async click(){


    const url = "../1.1.bartin/controller/click.php";
    const url_page = window.location.href;
    const data = { 
      action: "click",
      url_page: url_page
    };


    const response = await this.makeclick(url,data); //argumento

    if (!response) return;
    alert(JSON.stringify(response));
  }
  async makeclick(url, data) {
    // alert(JSON.stringify (data));
    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });


      if (!response.ok) {
        throw new Error("Network error.");
      }

      return await response.json();

    } catch (error) {

      console.error("Error:", error);
      return null;
    }
  }
}
const classClick = new ClassClick();
