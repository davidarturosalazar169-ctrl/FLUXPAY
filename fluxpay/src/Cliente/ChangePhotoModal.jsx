import { Modal, Button, Form, Image } from "react-bootstrap";
import { useState, useEffect } from "react";

const ChangePhotoModal = ({ show, handleClose, currentPhoto, onSave }) => {
  const [preview, setPreview] = useState(null);

  // Resetear preview cuando se cierre
  useEffect(() => {
    if (!show) {
      setPreview(null);
    }
  }, [show]);

  const handleChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onloadend = () => {
      setPreview(reader.result);
    };

    reader.readAsDataURL(file);
  };

  const handleSave = () => {
    if (preview) {
      onSave(preview);
    }
    handleClose();
  };

  return (
    <Modal show={show} onHide={handleClose} centered>
      <Modal.Header closeButton>
        <Modal.Title className="fw-bold">Cambiar foto</Modal.Title>
      </Modal.Header>

      <Modal.Body className="text-center">
        <p className="fw-semibold">Foto actual</p>
        <Image
          src={currentPhoto}
          roundedCircle
          width={120}
          height={120}
          className="mb-3"
          style={{ objectFit: "cover" }}
        />

        {preview && (
          <>
            <p className="fw-semibold">Nueva foto</p>
            <Image
              src={preview}
              roundedCircle
              width={120}
              height={120}
              className="mb-3"
              style={{ objectFit: "cover" }}
            />
          </>
        )}

        <Form.Control
          type="file"
          accept="image/*"
          onChange={handleChange}
        />
      </Modal.Body>

      <Modal.Footer>
        <Button variant="secondary" onClick={handleClose}>
          Cancelar
        </Button>
        <Button variant="primary" onClick={handleSave}>
          Guardar cambios
        </Button>
      </Modal.Footer>
    </Modal>
  );
};

export default ChangePhotoModal;