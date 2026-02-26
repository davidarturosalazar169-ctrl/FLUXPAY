import "./Styles/StyleNavbar.css";
function Navbar({nombre, rol,correo}) {

  return (
    <div className="navbar">
      <div>
        <h2 className="title">Dashboard {rol}</h2>
        <span className="subtitle">Hola {nombre}</span>
      </div>

      <div className="user-info">
        <img
          src="https://i.pravatar.cc/40"
          alt="User"
          className="user-img"
        />
        <div>
          <p className="user-name">
            {nombre}
          </p>
          <span className="user-email">{correo}</span>
        </div>
      </div>
    </div>
  );
}
export default Navbar