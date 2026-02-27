import React, { useState } from "react";
import Cards from "react-credit-cards-2";
import "react-credit-cards-2/dist/es/styles-compiled.css";
import { Modal } from "react-bootstrap";

const PaymentForm = ({ show, handleCerrar, handlesave }) => {
  const [state, setState] = useState({
    number: "",
    expiry: "",
    cvc: "",
    name: "",
    focus: "",
  });

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setState((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleInputFocus = (e) => {
    setState((prev) => ({
      ...prev,
      focus: e.target.name,
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // 🔥 FORZAMOS QUE SE GUARDE MOSTRANDO EL FRENTE
    handlesave({
      ...state,
      focus: "number",
    });

    // 🔄 Limpiar formulario
    setState({
      number: "",
      expiry: "",
      cvc: "",
      name: "",
      focus: "",
    });
  };

  return (
    <Modal show={show} onHide={handleCerrar} centered>
      <Modal.Body>
        <Cards
          number={state.number}
          expiry={state.expiry}
          cvc={state.cvc}
          name={state.name}
          focused={state.focus}
        />

        <form className="mt-4" onSubmit={handleSubmit}>
          <input
            type="tel"
            name="number"
            className="form-control mb-3"
            placeholder="Card Number"
            value={state.number}
            onChange={handleInputChange}
            onFocus={handleInputFocus}
          />

          <input
            type="text"
            name="name"
            className="form-control mb-3"
            placeholder="Name on Card"
            value={state.name}
            onChange={handleInputChange}
            onFocus={handleInputFocus}
          />

          <div className="row">
            <div className="col">
              <input
                type="tel"
                name="expiry"
                className="form-control mb-3"
                placeholder="MM/YY"
                value={state.expiry}
                onChange={handleInputChange}
                onFocus={handleInputFocus}
              />
            </div>

            <div className="col">
              <input
                type="tel"
                name="cvc"
                className="form-control mb-3"
                placeholder="CVC"
                value={state.cvc}
                onChange={handleInputChange}
                onFocus={handleInputFocus}
              />
            </div>
          </div>

          <button type="submit" className="btn btn-primary w-100">
            Guardar
          </button>
        </form>
      </Modal.Body>
    </Modal>
  );
};

export default PaymentForm;