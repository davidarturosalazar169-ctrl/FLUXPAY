import "../styles.css";
import Navbar from "../Navbar";
import "bootstrap/dist/css/bootstrap.min.css";
import { useState } from "react";
import TarjetasCliente from "./TarjetasCliente";
import { Button, Card, Row, Col } from "react-bootstrap";
import PaymentForm from "./PaymentForm";

function ClienteTarjeta() {
  const [mostrar, setMostrar] = useState(false);
  const [tarjetas, setTarjetas] = useState([]);

  const handleMostrar = () => setMostrar(true);
  const handleCerrar = () => setMostrar(false);

  const handlesave = (data) => {
    setTarjetas((prev) => [data, ...prev]);
    setMostrar(false);
  };

  const borrarTarjeta = (index) => {
    setTarjetas((prev) => prev.filter((_, i) => i !== index));
  };

  return (
    <div className="page">
      
      {/* Sidebar */}
      <div className="side-bar">
        <div className="logo-container">
          <img
            src="/fluxpay.jpg"
            alt="FluxPay Logo"
            className="logo-img"
          />
        </div>
      </div>

      {/* Contenido principal */}
      <div className="main-content">
        
        <Navbar
          nombre="Alexander Castillo"
          correo="Alexander.Correo@Gmail.com  "
          rol="Cliente"
        />

        <div className="container mt-4">
          <Row className="g-4">
            {tarjetas.length > 0 ? (
              tarjetas.map((tarjeta, index) => (
                <Col key={index} md={6} lg={4}>
                  <Card className="shadow-sm h-100">
                    <Card.Body>
                      <TarjetasCliente
                        number={tarjeta.number}
                        expiry={tarjeta.expiry}
                        cvc={tarjeta.cvc}
                        name={tarjeta.name}
                        focused={tarjeta.focus}
                      />
                      <Button
                        variant="danger"
                        className="mt-3 w-100"
                        onClick={() => borrarTarjeta(index)}
                      >
                        Borrar Tarjeta
                      </Button>
                    </Card.Body>
                  </Card>
                </Col>
              ))
            ) : (
              <Col>
                <Card className="text-center shadow-sm">
                  <Card.Body>
                    <Card.Title>No hay tarjetas registradas</Card.Title>
                    <Card.Text>
                      Agrega una nueva tarjeta para comenzar a realizar pagos.
                    </Card.Text>
                    <Button variant="warning" onClick={handleMostrar}>
                      Agregar Tarjeta
                    </Button>
                  </Card.Body>
                </Card>
              </Col>
            )}
          </Row>

          {tarjetas.length > 0 && (
            <div className="text-center mt-4">
              <Button variant="warning" onClick={handleMostrar}>
                Agregar Nueva Tarjeta
              </Button>
            </div>
          )}
        </div>

        <PaymentForm
          show={mostrar}
          handleCerrar={handleCerrar}
          handlesave={handlesave}
        />
      </div>
    </div>
  );
}

export default ClienteTarjeta;