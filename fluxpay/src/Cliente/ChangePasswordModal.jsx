import { Modal, Button, Form } from "react-bootstrap";
import { useState } from "react";

const ChangePasswordModal = ({ show, handleClose, currentPassword, onSave }) => {
  const [current, setCurrent] = useState("");
  const [newPass, setNewPass] = useState("");
  const [confirm, setConfirm] = useState("");

  const handleSubmit = () => {
    if (!current || !newPass || !confirm) {
      alert("Completa todos los campos");
      return;
    }

    if (current !== currentPassword) {
      alert("Contraseña actual incorrecta");
      return;
    }

    if (newPass !== confirm) {
      alert("Las contraseñas no coinciden");
      return;
    }

    onSave(newPass);
    handleClose();
  };

  return (
    <Modal show={show} onHide={handleClose} centered backdrop="static">
      <Modal.Header closeButton>
        <Modal.Title className="fw-bold">Cambiar contraseña</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <Form.Group className="mb-3">
          <Form.Label>Contraseña actual</Form.Label>
          <Form.Control
            type="password"
            value={current}
            onChange={(e) => setCurrent(e.target.value)}
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Nueva contraseña</Form.Label>
          <Form.Control
            type="password"
            value={newPass}
            onChange={(e) => setNewPass(e.target.value)}
          />
        </Form.Group>

        <Form.Group>
          <Form.Label>Confirmar nueva contraseña</Form.Label>
          <Form.Control
            type="password"
            value={confirm}
            onChange={(e) => setConfirm(e.target.value)}
          />
        </Form.Group>
      </Modal.Body>

      <Modal.Footer>
        <Button variant="secondary" onClick={handleClose}>
          Cancelar
        </Button>
        <Button variant="danger" onClick={handleSubmit}>
          Actualizar
        </Button>
      </Modal.Footer>
    </Modal>
  );
};

export default ChangePasswordModal;